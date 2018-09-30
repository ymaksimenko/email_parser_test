<?php

namespace App\Controller;

use App\Core\AbstractController;
use App\Model\UrlParser;
use App\Model\EmailParser;

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

        $lavel = !empty($this->getRequest()->getPost('lavel')) ? (int) $this->getRequest()->getPost('lavel') : 0;

        if (!empty($url)) {
            $url_parser = new UrlParser();
            $parse_urls = $url_parser->Parse($url, $lavel);

            $email_parser = new EmailParser();
            $result = $email_parser->Parse($parse_urls);

        }

        $this->getViev()->setViewParam('email_list', $result);
        $this->getViev()->setViewParam('email_count', count($result));
        $this->getViev()->setViewParam('site', $url);

        $this->getViev()->render();
    }
}