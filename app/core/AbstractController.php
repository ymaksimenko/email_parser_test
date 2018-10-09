<?php

namespace App\Core;

use App\Core\View;
use App\Core\Request;

class AbstractController
{
    private $viev;
    private $request;

    public function __construct($controller = 'index', $action = 'index', $connector = null)
    {
        $this->request = new Request();
        $this->viev = new View($controller, $action);
        $this->db_connector = $connector;
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