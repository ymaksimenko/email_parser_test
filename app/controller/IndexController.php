<?php

namespace App\Controller;
use App\Core\AbstractController;
use App\Core\View;
use MongoDB\Client as MongoClient;
use App\Core\AbstractModel;

class IndexController extends AbstractController
{

    public function indexAction()
    {
        $this->getViev()->render();
    }

    public function showresultAction ()
    {
        $db = AbstractModel::getDB();

        $sites = $db->sites->find();

        $this->getViev()->setViewParam('sites', $sites);

        $this->getViev()->render();
    }

    public function viewpagesAction()
    {
        $id = $this->getRequest()->getParam('site');

        if (!empty($id)) {

            $db = AbstractModel::getDB();

            $pages = $db->site_pages->find(["site" => $id])->toArray();
            $this->getViev()->setViewParam('pages', $pages);

            $this->getViev()->render();
        }
    }
}