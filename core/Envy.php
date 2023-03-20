<?php
namespace QuickieDox;

use RuntimeException;

class Envy
{
    /**
     * Load .env file variables into $_ENV
     * @param string $path
     * @return void
     */
    public static function load(string $path) :void
    {
        if (!is_readable($path)) {
            throw new RuntimeException(sprintf('%s file is not readable', $path));
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {

            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (! array_key_exists($name, $_SERVER) && ! array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
