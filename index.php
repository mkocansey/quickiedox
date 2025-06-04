<?php

declare(strict_types=1);

session_start();

require 'vendor/autoload.php';

use QuickieDox\Envy;
use QuickieDox\Router;
use QuickieDox\Request;
use Exception;

try {
    // Load environment variables
    if (!file_exists(__DIR__ . '/.env')) {
        throw new Exception('Environment file not found. Please rename .env-example to .env');
    }
    Envy::load(__DIR__ . '/.env');

    // Bootstrap application
    require 'core/bootstrap.php';

    // Handle routing
    Router::load('routes.php')
        ->direct(Request::uri(), Request::method());
} catch (Exception $e) {
    // Basic error handling - you might want to replace this with proper error handling
    http_response_code(500);
    echo $e->getMessage();
    exit(1);
}