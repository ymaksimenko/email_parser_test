<?php

namespace App\Core;

use App\Core\View;
use App\Core\Request;

class AbstractController
{
    private $viev;
    private $controller_name;
    private $action_name;
    private $request;


    public function __construct($controller = 'index', $action = 'index')
    {
        $this->request = new Request();
        $this->viev = new View($controller, $action);
    }

    public function getViev()
    {
        return $this->viev;
    }

    public function getRequest()
    {
        return $this->request;
    }

}