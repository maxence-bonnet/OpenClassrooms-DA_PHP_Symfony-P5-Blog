<?php
require '../config/developpement.php';
// require '../config/production.php';
require '../vendor/autoload.php';

session_start();
$router = new \App\config\Router();
$router->run();