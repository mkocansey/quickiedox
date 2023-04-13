<?php
use QuickieDox\App;
use QuickieDox\Doc;

/**
 * @param string $name
 * @param array $data
 * @param bool $raw
 * @return false|mixed|string
 */
function view (string $name, array $data = [], bool $raw = false)
{
    if (file_exists("views/$name.php") ) {
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

/**
 * @param string $key
 * @param string $specific_array
 * @return false|object
 */
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

    if($key !== '' ) {
        if (property_exists($array, $key) ) {
            if (!empty($array->$key) ) {
                return $array->$key;
            }
            return false;
        }
        return false;
    } else {
        return $array;
    }
}

/**
 * @param string $url
 * @param int $position
 * @return string
 */
function strip_slash(string $url, int $position = 0): string
{
    return
        (substr($url, $position) === '/') ?
        str_replace(substr($url, $position), '', $url) :
        $url;
}

/**
 * @param string $string
 * @return string
 */
function append_slash(string $string): string
{
    return (substr($string, -1) !== '/') ? $string . '/' : $string;
}

/**
 * @return string
 * @throws Exception
 */
function get_url_prefix(): string
{
    $url_prefix = strip_slash(App::get('docs_url_prefix'));
    return ($url_prefix !== '') ? append_slash($url_prefix) : $url_prefix;
}

/**
 * @param ...$params
 * @return false|string
 */
function api_response (...$params)
{
    $params = (object) $params[0];
    $return = [ "status" => $params->status ];
    if (isset($params->data) ) $return["data"] = $params->data;
    if (isset($params->message) ) $return["message"] = $params->message;
    if(! $params->status ) {
        http_response_code($params->http_response_code ?? 422); //422
    }
    return to_json($return);
}

function get_input()
{
    return json_decode(file_get_contents('php://input'));
}

/**
 * @param array $data
 * @return false|string
 */
function to_json (array $data)
{
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data);
}

/**
 * @return string
 * @throws Exception
 */
function docs_home(): string
{
    return '/' . get_url_prefix().
        append_slash(variable('current_version','session')??App::get('default_doc_version')).
        Doc::stripMdExtension(App::get('default_doc_page'));
}

/**
 * @param string $content
 * @param string $keyword
 * @return array|string|string[]|null
 */
function highlight_search(string $content, string $keyword)
{
    return preg_replace("/$keyword/", '<code class="inline">'.$keyword.'</code>', $content);
}

/**
 * @param string $url
 * @return void
 */
function redirect(string $url)
{
    die("<script>window.location.replace('{$url}');</script>");
}

/**
 * @param string $url
 * @param array $data
 * @param string $method
 * @param bool $decode
 * @return mixed
 * @throws Exception
 */
function call_api(string $url, array $data = [], string $method = 'POST', bool $decode = true)
{
    $client = new Client();
    if ($method == 'POST') {
        $res = $client->post(
            $url,
            [
                'body' => json_encode($data),
                'verify' => (App::get('environment') === 'production'),
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'Cache-Control' => 'no-cache'
                ],
            ]
        );
    } else {
        $res = $client->get($url);
    }
    return (($decode) ? json_decode($res->getBody()) : $res->getBody());
}

if (!function_exists('str_ends_with')) {
    /**
     * Adding PHP 7 compatibility
     * @param $str
     * @param $end
     * @return bool
     */
    function str_ends_with($str, $end): bool
    {
        return (@substr_compare($str, $end, -strlen($end))==0);
    }
}

if (!function_exists('str_contains')) {
    /**
     * Adding PHP 7 compatibility
     * @param $haystack
     * @param $needle
     * @return bool
     */
    function str_contains($haystack, $needle): bool
    {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}



/*    function replace_version(string $string, string $version): string
{
    return preg_replace('/\/+/', '/', (str_replace('%7Bversion%7D', '/' . get_url_prefix() . $version, $string)));
}*/