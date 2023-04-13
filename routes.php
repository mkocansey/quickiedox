<?php
    use QuickieDox\Router;

    $url_prefix = get_url_prefix();

    Router::get('', 'DocController@index');
    Router::get('clone', 'DocController@clone_init');
    Router::post('clone', 'DocController@clone_init');
    Router::get('cloning', 'DocController@clone');
    Router::get('search', 'DocController@search');

    if($url_prefix !== '') {
        Router::get(strip_slash($url_prefix, -1), 'DocController@read');
    }

    Router::get($url_prefix .'{version}',  'DocController@read');
    Router::get($url_prefix .'{page}',  'DocController@read');
    Router::get($url_prefix .'{version}/{page}', 'DocController@read');
    Router::get($url_prefix .'404', 'DocController@notFound');

    // possible auth urls if you require users to sign in before reading docs
    // Router::get('token/validate', 'AuthController@validate');
    // Router::get('signout', 'AuthController@signout');