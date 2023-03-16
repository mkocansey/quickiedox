<!doctype html>
<html>
<head>
    <title><?php echo $page_title ?? \App\Core\App::get('default_page_title') ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="/assets/images/favicon.png" class="touch-icon" />
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon" class="fav-icon" />
    <link href="/assets/css/prism.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href="/assets/css/quickiedox.css?<?php echo uniqid(); ?>" rel="stylesheet">
</head>