<?php
    use App\Core\Utilities;
    use App\Core\App;
    use GuzzleHttp\Client;

    function view (string $name, array $data = [], bool $raw = false)
    {
        if( file_exists("views/{$name}.php") )
        {
            extract($data);
            if (! $raw ) {
                require "views/{$name}.php";
            } else {
                ob_start();
                require "views/{$name}.php";
                $res = ob_get_contents();
                ob_get_clean();
                ob_flush();
                return $res;
            }
        } else {
            require "views/404.php";
        }
    }

    function redirect(string $url): void
    {
        echo '<script type="text/javascript">location.href=\''.$url.'\';</script>';
    }

    function callAPI($url, $data=[], $method='POST', $decode=true) {

        $client = new Client();
        if ($method === 'POST') {
            $res = $client->post(
                App::get('api_url') . $url,
                [
                    'body' => json_encode($data)
                ]
            );
        } else {
            $res = $client->get(App::get('api_url') . $url);
        }
        return (($decode) ? json_decode($res->getBody()) : $res->getBody());
    }

    function string_contains(string $string, string $keyword): bool
    {
        return ($string !== '' && $keyword !== '' && strstr($string, $keyword) != '');
    }

    function variable(string $key = '', string $specific_array = ''): object|bool|array
    {
        switch ($specific_array){
            case 'post':
                $array = (object) $_POST;
                break;
            case 'get':
                $array = (object) $_GET;
                break;
            case 'file':
                $array = (object) $_FILES;
                break;
            case 'session':
                $array = (object) $_SESSION;
                break;
            default:
            case "":
                $array = (object) array_merge($_POST, $_GET, $_SESSION, $_FILES);
                break;
        }

        if( $key !== '' ) {
            if ( property_exists($array, $key) ) {
                if ( !empty($array->$key) ) {
                    return $array->$key;
                }
                return false;
            }
            return false;
        } else {
            return $array;
        }
    }

    function http_get_contents($url, $data=[], $username='', $password='')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        if (App::get('app_mode') === 'development') curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        if ( count($data) > 0 ){
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }
        if ( $username != '' && $password != '' ) {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(FALSE === ($retval = curl_exec($ch))){
            echo curl_error($ch);
        } else {
            return $retval;
        }
    }

    function strip_slash(string $url, int $position = 0): string
    {
        return
            (substr($url, $position) === '/') ?
            str_replace(substr($url, $position), '', $url) :
            $url;
    }

    function get_url_prefix(): string
    {
        return strip_slash(App::get('docs_url_prefix'));
    }

    function replace_version(string $string, string $version): string
    {
        return preg_replace('/\/+/', '/', (str_replace('%7Bversion%7D', '/' . get_url_prefix() . $version, $string)));
    }