<?php
switch ($modx->event->name) {
    case 'OnWebPagePrerender':

        if ($modx->context->get('key') === "mgr") {
            break;
        }

        if ($modx->user->hasSessionContext('mgr')) {
            $jsonOutput = array();
            $appname =  'queeg';

            $package = $modx->getObject('transport.modTransportPackage', array('package_name' => 'queeg'));

            if ($package) {
                $major = $package->get('version_major');
            }

            $userSettings = $modx->user->getSettings();
            $lang = $userSettings['manager_language'];

            if (!$lang) {
                $lang = $modx->getOption('manager_language', null, 'en');
            }

            $api =  $major;
            $param['id'] =  true;
            $param['published'] = (boolean) $modx->getOption('modxchromemanager.published', null, true);
            $param['editedon'] = (boolean) $modx->getOption('modxchromemanager.editedon', null, true);
            $param['editedby'] = (boolean) $modx->getOption('modxchromemanager.editedby', null, true);
            $custom_fields = $modx->getOption('modxchromemanager.custom_fields', null, false);

            $systemFields = array('id', 'published');

            if ($custom_fields) {
                $customArray = array_map('trim', explode(",", $custom_fields)); // explode and trim
                $custom_params = array_fill_keys($customArray, true); // Change val => key and set true
                $param = array_merge($param, $custom_params); // merge default and custom
            }

            $params = array_filter($param); // remove false options

            $modx->setOption('cultureKey', $lang);
            $modx->lexicon->load('core:default');

            foreach ($params as $key => $value) {

                $data = $modx->resource->$key; // get each placeholder from resource (id, published, ...)

                if ($key == 'editedby') {
                    if ($data) {
                        $data = $modx->getObject('modUser', $data)->get('username');
                    }
                }

                // System Values
                if (in_array($key, $systemFields)) {
                    $jsonOutputSystem[$key] = $data;
                }

                $key = str_replace("'", '&apos;', $modx->lexicon($key));
                // $key = $modx->lexicon($key);
                $jsonOutput[$key] = $data;
            }

            $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

            // Add system params
            $jsonOutputSystem['host'] = $protocol . $_SERVER['HTTP_HOST'];
            $jsonOutputSystem['manager'] = MODX_MANAGER_URL;

            $jsonSystem = json_encode($jsonOutputSystem);
            $json = json_encode($jsonOutput);

            $output = &$modx->resource->_output;
            $output = preg_replace('/(<\/head>(?:<\/head>)?)/i',"<meta name='{$appname}' content='{$json}' data-system='{$jsonSystem}' data-api='{$api}' />\r\n$1", $output);
        }
        break;
}