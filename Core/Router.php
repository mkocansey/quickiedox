<?php

namespace App\Core;


class Router
{
    public static $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file): static
    {
        // $router = new static;
        require $file;
        return new static;
    }

    public static function get($url, $controller): void
    {
        self::$routes['GET'][strip_slash($url)] = $controller;
    }

    public static function post($url, $controller): void
    {
        self::$routes['POST'][strip_slash($url)] = $controller;
    }

    public function direct($url, $requestType)
    {
        $this->hotSwap($url, $requestType);

        if ( array_key_exists($url, self::$routes[$requestType]) ) {
            return $this->callAction(
                ...explode('@', self::$routes[$requestType][strip_slash($url)])
            );
        }

        $this->direct('404','GET');
    }

    public function callAction($controller, $action)
    {
        $controller_ = $controller;
        $controller = "App\\Controllers\\{$controller}";
        $controller = new $controller;

        if ( !method_exists($controller, $action) )
        {
            throw new \Exception("{$controller_} does not respond to @{$action}");
        }
        return $controller->$action();
    }

    private function  hotSwap($url, $requestType)
    {
        $url_parts = explode('/', $url);
        $array_keys = (array_keys(self::$routes[$requestType]));
        $dynamic_route_params = [];
        Session::forget(['dynamic_route_params']);

        foreach ($array_keys as $key) {
//            echo $key;
//            var_dump(substr_count($url, '/'));
            if( substr_count($key, '/') === substr_count($url, '/') && strstr($key, '{')  ) {
                $key_parts = explode('/', $key);
                for ($x=0; $x < count($key_parts); $x++) {
                    if( strstr($key_parts[$x], '{') ) {
                        $param_key = preg_replace('/[{,}]/', '', $key_parts[$x]);
                        $dynamic_route_params[$param_key] =  $url_parts[$x];
                        $key_parts[$x] = $url_parts[$x];
                    }
                }
                if ( implode('/',$key_parts) === $url){
                    self::$routes[$requestType][implode('/',$key_parts)] = self::$routes[$requestType][$key];
                    unset(self::$routes[$requestType][$key]);
                    if( count($dynamic_route_params) > 0 ) Session::put([ 'dynamic_route_params' => $dynamic_route_params ]);
                    break;
                }
            }
        }
    }
}