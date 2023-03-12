<?php
    use \App\Core\Router;

    $url_prefix = get_url_prefix();

    Router::get('', 'DocController@index');

    if($url_prefix !== '') {
        Router::get(strip_slash($url_prefix, -1), 'DocController@read');
    }

    Router::get($url_prefix .'{page}', 'DocController@read');
    Router::get($url_prefix .'{version}/{page}', 'DocController@read');
    Router::get($url_prefix .'404', 'DocController@notFound');
    Router::get($url_prefix .'restricted', 'DocController@restricted');