<?php

class Router {

    private $routes;

    public function __construct()
    {
        $routesPath = ROOT.'/config/routes.php';
        //Присваивается масивв из routes.php
        $this->routes = include ($routesPath);
    }

    /*
     * Returns reqiest string
     */
    private function getURI(){
        //Получить строку запроса
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], 'magaz/');
        }
    }

    public function run()
    {
        //Получить строку запроса
        $uri = $this->getURI();

        //Проверить наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path){

            //Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)){

                //< ЧПУ!!!!!
                // Получаем внутренний путь из внешнего согласно правилу

                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
                
                //("([a-z]+)/([0-9]+)", news/views/$1/$2, news/sport/114)
                //>

//было          //Определить какой контроллер
                //и экшн обрабатывает запрос
//                $segments = explode('/', $path);
//
//                $controllerName = array_shift($segments).'Controller';
//                $controllerName = ucfirst($controllerName);
//
//                $actionName = 'action'.ucfirst(array_shift($segments));

//стало         //Определить контроллер, экшн, параметры
                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action'.ucfirst(array_shift($segments));

                $parameters = $segments;

                //Подключить файл класса контроллера
                $controllerFile = ROOT . '/controllers/' .
                    $controllerName . '.php';

                if (file_exists($controllerFile)){
                    include_once ($controllerFile);
                }

                //Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;

                //$result = $controllerObject->$actionName($parameters);
                $result = call_user_func_array(array($controllerObject, $actionName),$parameters);
                
                if ($result != null) {
                    break;
                }
            }
        }
    }
}