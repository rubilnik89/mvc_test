<?php

//<включение отображения ошибок
ini_set('display_errors',1 );
error_reporting(E_ALL);
//>отключить когда сайт в продакшн

//<Подключаю файл роутер
define('ROOT', dirname(__FILE__));
require_once (ROOT.'/components/Router.php');
//>

//BD
require_once (ROOT.'/components/Db.php');

$router = new Router();
$router->run();