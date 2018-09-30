<?php
namespace App\Core;


class Request
{
    private $post_params = [];
    private $get_params = [];

    public function __construct()
    {
        $this->get_params = $_GET;
        $this->post_params = $_POST;
    }

    public function getPost($param = '')
    {
        if (!empty($param)) {
            return $this->post_params[$param];
        } else {
            return $this->post_params;
        }
    }

    public function getParam($param = '')
    {
        if (!empty($param)) {
            return $this->get_params[$param];
        } else {
            return $this->get_params;
        }
    }


}