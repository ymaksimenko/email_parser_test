<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Model\Parser;

class ParseController extends AbstractController
{

    public function indexAction()
    {
        $this->getViev()->render();
    }

    public function startAction()
    {
        $result = [];

        $url = trim($this->getRequest()->getPost('url'));
        $url =  filter_var($url, FILTER_VALIDATE_URL) ? $url : '';

        $lavel = (int) $this->getRequest()->getPost('lavel');
        $max_count = (int) $this->getRequest()->getPost('max_count');

        if (!empty($url) && $max_count > 0) {
            $parser = new Parser();
            $parse_result = $parser->LinearParserStart($url, $lavel, $max_count);
        }

        $db = Parser::getDB();

        $sites = $db->sites->findOne(['site'=>$url]);
        $pages = $db->site_pages->find();

        $this->getViev()->setViewParam('sites', $sites);
        $this->getViev()->setViewParam('pages', $pages);

        $this->getViev()->setViewParam('link_count', $parse_result['link_count']);
        $this->getViev()->setViewParam('email_count', $parse_result['email_count']);
        $this->getViev()->setViewParam('site', $url);

        $this->getViev()->render();
    }

}