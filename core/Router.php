<?php
namespace QuickieDox;

use Exception;

class Router
{
    /**
     * @var array|array[]
     */
    public static array $routes = [
        'GET' => [],
        'POST' => []
    ];

    /**
     * @param string $file
     * @return Router
     */
    public static function load(string $file): Router
    {
        require $file;
        return new static;
    }

    /**
     * @param string $url
     * @param string $controller
     * @return void
     */
    public static function get(string $url, string$controller): void
    {
        self::$routes['GET'][strip_slash($url)] = $controller;
    }

    /**
     * @param string $url
     * @param string $controller
     * @return void
     */
    public static function post(string $url, string $controller): void
    {
        self::$routes['POST'][strip_slash($url)] = $controller;
    }

    /**
     * @throws Exception
     */
    public function direct(string $url, string $requestType)
    {
        $this->hotSwap($url, $requestType);

        if (array_key_exists($url, self::$routes[$requestType]) ) {
            return $this->callAction(
                ...explode('@', self::$routes[$requestType][strip_slash($url)])
            );
        }

        return $this->direct('404','GET');
    }

    /**
     * @throws Exception
     */
    public function callAction(string $controller, string $action)
    {
        $controller_ = $controller;
        $controller = "QuickieDox\\Controllers\\$controller";
        $controller = new $controller;

        if (!method_exists($controller, $action) )
        {
            throw new Exception("$controller_ does not respond to @$action");
        }
        return $controller->$action();
    }

    /**
     * @param string $url
     * @param string $requestType
     * @return void
     */
    private function hotSwap(string $url, string $requestType)
    {
        $url_parts = explode('/', $url);
        $array_keys = (array_keys(self::$routes[$requestType]));
        $dynamic_route_params = [];
        Session::forget(['dynamic_route_params']);

        foreach ($array_keys as $key) {
            if (substr_count($key, '/') === substr_count($url, '/') && strstr($key, '{')  ) {
                $key_parts = explode('/', $key);
                for ($x=0; $x < count($key_parts); $x++) {
                    if (strstr($key_parts[$x], '{') ) {
                        $param_key = preg_replace('/[{,}]/', '', $key_parts[$x]);
                        $dynamic_route_params[$param_key] =  $url_parts[$x];
                        $key_parts[$x] = $url_parts[$x];
                    }
                }
                if (implode('/',$key_parts) === $url){
                    self::$routes[$requestType][implode('/',$key_parts)] = self::$routes[$requestType][$key];
                    unset(self::$routes[$requestType][$key]);
                    if (count($dynamic_route_params) > 0 ) Session::put([ 'dynamic_route_params' => $dynamic_route_params ]);
                    break;
                }
            }
        }
    }
}