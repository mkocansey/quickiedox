<?php
    use QuickieDox\App;

    App::bind('config', require 'config.php');

    $config_array = App::get('config');

    /**
     * bind all values defined in the config.php to a key/value variable name
     * that can be retrieved using App::get('variable_name')
     */
    if (is_array($config_array) && ! empty($config_array)) {
        foreach ($config_array as $key => $value) {
            // just ensure true/false values from .env file are treated as boolean
            $value = (!is_array($value) && (str_contains($value, 'true') ||
                str_contains($value, 'false'))) ?
                filter_var($value, FILTER_VALIDATE_BOOLEAN) :
                $value;
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