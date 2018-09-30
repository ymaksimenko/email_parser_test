<?php
namespace App\Core;


class Router
{
    private $route;

    private $controller;

    private $action;


    public function __construct()
    {
        $this->route = include APP_PATH . '/config/routes.php';
    }

    public function Routing()
    {
        $path_arr = explode('/', $_SERVER["REQUEST_URI"]);

        $this->controller = !empty($path_arr[1]) ? $path_arr[1] : 'index';

        $this->action = !empty($path_arr[2]) ? $path_arr[2] : 'index';

        if ($this->controller !== 'Index' && array_key_exists($this->controller, $this->route)) {
            if (array_search($this->action, $this->route[$this->controller]['actions']) === false) {
                $this->controller = 'Error404';
                $this->action = 'index';
            }
        } else {
            $this->controller = 'Error404';
            $this->action = 'index';
        }
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

}