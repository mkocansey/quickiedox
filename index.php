<?php
    @session_start();

    use App\Core\Envy;
    use App\Core\Router;
    use App\Core\Request;

    require 'vendor/autoload.php';

    /**
     * load the content of the .env file
     */
    if(! file_exists(__DIR__ . '/.env')) die('Please rename .env-example to .env');
    Envy::load(__DIR__ . '/.env');

    /**
     * load all variables defined in config.php and make them accessible globally
     */
    require 'Core/bootstrap.php';

    /**
     * load routes from the routes.php file
     * mapping of routes to their respective controllers is handed in Core/Router.php
     */
    Router::load('routes.php')->direct(Request::uri(), Request::method());