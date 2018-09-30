<?php
namespace App\Core;
use App\Core\Router;
use MongoDB;

class Application
{

    private $databaseConnector;

    private $router;

    public function __construct()
    {
       // $this->databaseConnector = new Mongo();

    }

    public function start()
    {
        $router = new Router();

        $router->Routing();

        $this->router = $router;

        $comtroller_name = 'App\Controller\\' . ucfirst($router->getController()) . 'Controller';
        $action_name = $router->getAction() . 'Action';
        $controller = new $comtroller_name($router->getController(), $router->getAction());

        $controller->$action_name();

    }

    public function getDatabaseConnector()
    {
        return $this->databaseConnector;
    }

}