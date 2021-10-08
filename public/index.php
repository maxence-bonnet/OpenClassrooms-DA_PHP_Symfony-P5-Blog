<?php

define('PAGE_GENERATING_TIME', microtime(true));

require dirname(__DIR__) . '/config/developpement.php';
// require dirname(__DIR__) . '/config/production.php';
require dirname(__DIR__) . '/vendor/autoload.php';

session_start([
    'name' => 'SESSID',
    'cookie_lifetime' => 2592000,
    'use_only_cookies' => true,
    'cookie_httponly' => 1
]);
$router = new \App\Config\Router();
$router->run();
