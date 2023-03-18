<!doctype html>
<html class="scroll-smooth">
<head>
    <title><?php echo $page_title ?? \App\Core\App::get('default_page_title') ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="/assets/images/favicon.png" class="touch-icon" />
    <link rel="shortcut icon" href="/assets/images/favicon.png" type="image/x-icon" class="fav-icon" />
    <link href="/assets/css/prism.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="/assets/css/quickiedox.css?<?php echo uniqid(); ?>" rel="stylesheet">
    <script src="/assets/js/quickiedox.js"></script>
</head>