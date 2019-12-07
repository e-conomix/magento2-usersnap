# Usersnap Integration for Magento 2

## About
This extension brings [Usersnap](https://www.usersnap.com) widget to your Magento 2 Store.

## Features   
* You can seperatly define, if the widget should be shown in frontend and backend.
* You can define IP restrictions, that the widget is only shown to users coming from whitelisted IPs 

## Installation
Install the extension using composer: `composer require e-conomix/magento2-usersnap`
Afterwards enable the module: `bin/magento module:enable Economix_Usersnap`

## Configuration
In the backend under `Stores => Configuration => Services => Usersnap Integration` you can configure the plugin.

### Basic configuration
* Enable the plugin
* Add the `Project API Key` from your corresponding Usersnap project
* Define if the widget should be enabled in front- and/or backend

### IP restrictions
* Enable IP restrictions
* Enter IPs that should be able to access the Usersnap widget

## License
This module is open-sourced software licensed under the [GNU GPLv3](https://www.gnu.org/licenses/gpl-3.0.de.html). 
