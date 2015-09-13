# MODX Queeg
Queeg is a simple MODX Extra that provides a **one click** access to MODX Resources from a website to its manager.

Queeg works together with [MODX Queeg Chrome Extension](https://chrome.google.com/webstore/detail/modx-manager-switch/pchmcecidlmiajanecgkibndaoabncke)!

## What does it do?
In combination with [this Chrome extension](https://chrome.google.com/webstore/detail/modx-manager-switch/pchmcecidlmiajanecgkibndaoabncke) you can **easily open a resource for edititng** just by **one click**. 

As you can imagine, it's useful for editors (and also for you, developers).

![Screenshot](https://raw.githubusercontent.com/bartholomej/modx-manager-switch/master/_assets/screenshot_chrome-page-action.png) 

## Installation and how to use it?
1. Install **MODX Extra** Queeg through Package Management *(Extras → Installer → Download Extras → Queeg)*
2. Install **Chrome extension** [MODX Queeg](https://chrome.google.com/webstore/detail/modx-manager-switch/pchmcecidlmiajanecgkibndaoabncke)

## Technical details
Queeg injects meta data into the *html head* via a *MODX plugin*. Meta data are also used in the browser extension for information about the MODX Resource such as editedby, editedon, published etc.

### Output example
```html
<meta name='queeg' content='{"ID":6,"Published":0,"Edit Date":2015-12-11 10:20,"editedby":"admin","Resource&apos;s title":"test2","Alias":"index"}' data-system='{"id":6,"published":0,"host":"http:\/\/pkgs.modx.dev","manager":"\/manager\/"}' data-api='1' />
```

### Translation and localization
Meta data are transformed by following MODX system setting parameters:

- `manager_language` - Information in the browser extension are translated
- `manager_date_format` - Date format in datetime outputs

Can be defined on user as well.

### Security
Meta informations about resources are visible only for users that are logged in.

### System Settings
In MODX System Setting you can easily set which fields should be visible in your output.

- `published` : Whether or not the Resource is published
- `editedon`: Last edited date of the Resource
- `editedby`: User who last edited the Resource
- `custom_fields = ''` You can define comma separated list of additional fields (e.g.: pagetitle, alias)

## License

Copyright &copy; 2015 [Baoweb](http://github.com/baoweb)

Proudly powered by Beer, Curry and [Red Dwarf](http://www.imdb.com/title/tt0684175/) ;)

All contents are licensed under the [MIT license].

[MIT license]: LICENSE