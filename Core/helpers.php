<?php
    use App\Core\App;

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

    function variable(string $key = '', string $specific_array = ''): mixed //object|bool|array|string|int
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

    function append_slash(string $string): string
    {
        return (substr($string, -1) !== '/') ? $string . '/' : $string;
    }

    function get_url_prefix(): string
    {
        return append_slash(strip_slash(App::get('docs_url_prefix')));
    }

    function replace_version(string $string, string $version): string
    {
        return preg_replace('/\/+/', '/', (str_replace('%7Bversion%7D', '/' . get_url_prefix() . $version, $string)));
    }

    function exec_in_background(string $cmd, $result=null, $return=null) 
    {
        //taken from: https://www.php.net/manual/en/function.exec.php#86329
        if (substr(php_uname(), 0, 7) == "Windows"){
            pclose(popen("start /B ". $cmd, "r"));  
        } else {
            exec($cmd . " > /dev/null &", $result, $return);   
            if(count($result) > 0) {
                echo '<pre class="text-slate-400">';
                foreach($result as $line) echo "$line \n";
                echo '</pre>';
            }
        }
    }

    function api_response (...$params)
    {
        $params = (object) $params[0];
        $return = [ "status" => $params->status ];
        if ( isset($params->data) ) $return["data"] = $params->data;
        if ( isset($params->message) ) $return["message"] = $params->message;
        if(! $params->status ) {
            http_response_code(isset($params->http_response_code) ? $params->http_response_code : 422); //422
        }
        return jsonize($return);
    }

    function get_input()
    {
        return json_decode(file_get_contents('php://input'));
    }

    function jsonize ($data) {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }
