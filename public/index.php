<?php

define('PAGE_GENERATING_TIME', microtime(true));

require '../config/developpement.php';
// require '../config/production.php';
require '../vendor/autoload.php';

session_start();
$router = new \App\Config\Router();
$router->run();
