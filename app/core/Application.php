<?php
namespace App\Core;
use App\Core\Router;

class Application
{

    public function start()
    {
        $router = new Router();

        $router->Routing();

        $comtroller_name = 'App\Controller\\' . ucfirst($router->getController()) . 'Controller';
        $action_name = $router->getAction() . 'Action';
        $controller = new $comtroller_name($router->getController(), $router->getAction());

        $controller->$action_name();
    }

}