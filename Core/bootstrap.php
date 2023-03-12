<?php
    use App\Core\App;

    App::bind('config', require 'config.php');

    $config_array = App::get('config');

    /**
     * bind all values defined in the config.php to a key/value variable name
     * that can be retrieved using App::get('variable_name')
     */
    if(is_array($config_array) && ! empty($config_array)) {
        foreach ($config_array as $key => $value) {
            App::bind($key, $value);
        }
    }

    /**
     * all values in config.php can be accessed as App::get('variable_name').
     * the environment variable for example can be accessed as App::get('environment')
     * -------------------------------------------------------------------------
     * you can bind additional variables you want to access globally here.
     */

    App::bind('default_doc_version', (! empty(App::get('doc_versions')) ) ? App::get('doc_versions')[0] : '');