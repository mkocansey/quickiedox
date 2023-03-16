<?php
    use App\Core\App;
    use App\Core\Doc;

    function view (string $name, array $data = [], bool $raw = false)
    {
        if( file_exists("views/$name.php") )
        {
            extract($data);
            if (! $raw ) {
                return require "views/$name.php";
            } else {
                ob_start();
                require "views/$name.php";
                $res = ob_get_contents();
                ob_get_clean();
                ob_flush();
                return $res;
            }
        } else {
            return require "views/404.php";
        }
    }

    function variable(string $key = '', string $specific_array = '')
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
        if(FALSE === ($return = curl_exec($ch))){
            echo curl_error($ch);
        } else {
            return $return;
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

/*    function replace_version(string $string, string $version): string
    {
        return preg_replace('/\/+/', '/', (str_replace('%7Bversion%7D', '/' . get_url_prefix() . $version, $string)));
    }*/

    function api_response (...$params)
    {
        $params = (object) $params[0];
        $return = [ "status" => $params->status ];
        if ( isset($params->data) ) $return["data"] = $params->data;
        if ( isset($params->message) ) $return["message"] = $params->message;
        if(! $params->status ) {
            http_response_code($params->http_response_code ?? 422); //422
        }
        return to_json($return);
    }

    function get_input()
    {
        return json_decode(file_get_contents('php://input'));
    }

    function to_json ($data)
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data);
    }

    function docs_home(): string
    {
        return '/' . get_url_prefix().
            append_slash(App::get('default_doc_version')).
            Doc::stripMdExtension(App::get('default_doc_page'));
    }