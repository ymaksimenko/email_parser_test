<?php

namespace App\Controller;
use App\Core\AbstractController;
use App\Core\View;

class IndexController extends AbstractController
{

    public function indexAction() {
        $this->getViev()->render();
    }
}