<?php
    use \App\Core\Router;

    $url_prefix = get_url_prefix();

    Router::get('', 'DocController@index');
    Router::get('get-markdown', 'DocController@clone_init');
    Router::post('get-markdown', 'DocController@clone_init');
    Router::get('clone', 'DocController@clone');

    if($url_prefix !== '') {
        Router::get(strip_slash($url_prefix, -1), 'DocController@read');
    }

    Router::get($url_prefix .'{version}',  'DocController@read');
    Router::get($url_prefix .'{page}',  'DocController@read');
    Router::get($url_prefix .'{version}/{page}', 'DocController@read');
    Router::get($url_prefix .'404', 'DocController@notFound');
    Router::get($url_prefix .'restricted', 'DocController@restricted');