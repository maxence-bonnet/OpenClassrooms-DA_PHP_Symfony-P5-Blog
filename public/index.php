<?php

define('PAGE_GENERATING_TIME', microtime(true));

require '../config/developpement.php';
// require '../config/production.php';
require '../vendor/autoload.php';

session_start([
    'name' => 'SESSID',
    'cookie_lifetime' => 2592000,
    'use_only_cookies' => true,
    'cookie_httponly' => 1
]);
$router = new \App\Config\Router();
$router->run();
