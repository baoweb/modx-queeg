<?php
if ($modx->getOption('queeg.active', null, true)) {
    switch ($modx->event->name) {
        case 'OnWebPagePrerender':

            if ($modx->context->get('key') === "mgr") {
                break;
            }

            if ($modx->user->hasSessionContext('mgr')) {
                $appname =  'queeg';
                $contentArray = array();
                $systemArray = array();

                // get queeg version
                $package = $modx->getObject('transport.modTransportPackage', array('package_name' => 'queeg'));
                if ($package) {
                    $major = $package->get('version_major');
                }

                // get user's language
                $userSettings = $modx->user->getSettings();
                $lang = $userSettings['manager_language'];

                if (!$lang) {
                    $lang = $modx->getOption('manager_language', null, 'en');
                }

                $modx->setOption('cultureKey', $lang);
                $modx->lexicon->load('core:default');

                // Initialize system settings
                $api =  $major;
                $param['id'] =  true;
                $param['published'] = (boolean) $modx->getOption('queeg.published', null, true);
                $param['editedon'] = (boolean) $modx->getOption('queeg.editedon', null, true);
                $param['editedby'] = (boolean) $modx->getOption('queeg.editedby', null, true);
                $custom_fields = $modx->getOption('queeg.custom_fields', null, false);
                $date_format = $modx->getOption('queeg.date_format', null, 'Y-m-d H:i');

                // Define system fields
                $systemFields = array('id', 'published');

                if ($custom_fields) {
                    $cFields = array_map('trim', explode(",", $custom_fields)); // explode and trim
                    $cArray = array_fill_keys($cFields, true); // Change val => key and set true
                    $param = array_merge($param, $cArray); // merge default fields and custom fields
                }

                $params = array_filter($param); // remove false options

                foreach ($params as $key => $value) {

                    $data = $modx->resource->$key; // get each placeholder from resource (id, published, ...)

                    if ($key == 'editedby') {
                        if ($data) {
                            $data = $modx->getObject('modUser', $data)->get('username');
                        }
                    }

                    if ($key == 'editedon') {
                        $data = date($date_format, $data);
                    }

                    // System Values
                    if (in_array($key, $systemFields)) {
                        $systemArray[$key] = $data;
                    }

                    // Content values
                    $key = str_replace("'", '&apos;', $modx->lexicon($key));
                    $contentArray[$key] = $data;
                }

                $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';

                // Add system params
                $systemArray['host'] = $protocol . $_SERVER['HTTP_HOST'];
                $systemArray['manager'] = MODX_MANAGER_URL;

                $system = json_encode($systemArray);
                $content = json_encode($contentArray);

                $output = &$modx->resource->_output;
                $output = preg_replace('/(<\/head>(?:<\/head>)?)/i',"<meta name='{$appname}' content='{$content}' data-system='{$system}' data-api='{$api}' />\r\n$1", $output);
            }
            break;
    }
}