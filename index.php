<?php
    @session_start();

    require 'vendor/autoload.php';

    use QuickieDox\Envy;
    use QuickieDox\Router;
    use QuickieDox\Request;

    /**
     * load the content of the .env file
     */
    if(! file_exists(__DIR__ . '/.env')) die('Please rename .env-example to .env');
    Envy::load(__DIR__ . '/.env');

    /**
     * load all variables defined in config.php and make them accessible globally
     */
    require 'core/bootstrap.php';

    /**
     * load routes from the routes.php file
     * mapping of routes to their respective controllers is handed in core/Router.php
     */
    Router::load('routes.php')->direct(Request::uri(), Request::method());