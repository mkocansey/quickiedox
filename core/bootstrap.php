<?php

declare(strict_types=1);

use QuickieDox\App;

/**
 * Bootstrap application configuration
 * 
 * This file is responsible for loading and binding configuration values
 * to the application container.
 */

final class Bootstrap
{
    /**
     * Initialize the application configuration
     */
    public static function init(): void
    {
        $config = require 'config.php';
        if (!is_array($config)) {
            throw new RuntimeException('Configuration must be an array');
        }

        self::bindConfig($config);
        self::setDefaultDocVersion();
    }

    /**
     * Bind configuration values to the application container
     */
    private static function bindConfig(array $config): void
    {
        App::bind('config', $config);

        foreach ($config as $key => $value) {
            App::bind($key, self::normalizeValue($value));
        }
    }

    /**
     * Set the default documentation version
     */
    private static function setDefaultDocVersion(): void
    {
        $versions = App::get('doc_versions');
        $defaultVersion = is_array($versions) && !empty($versions) ? $versions[0] : '';
        App::bind('default_doc_version', $defaultVersion);
    }

    /**
     * Normalize configuration values
     */
    private static function normalizeValue(mixed $value): mixed
    {
        if (!is_string($value)) {
            return $value;
        }

        return match (strtolower($value)) {
            'true' => true,
            'false' => false,
            default => $value
        };
    }
}

// Initialize the application
Bootstrap::init();