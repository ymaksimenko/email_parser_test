<?php
namespace App\Core;


class View
{
    private $layout;
    private $view_params = [];
    private $content_layout;

    public function __construct($controller, $action, $params = [])
    {
        // Set maim layouy
        $this->layout = APP_PATH . '/view/layout.phtml';
        // Set layout content
        $content_layout_path = APP_PATH . '/view/' . $controller. '/'. $action . '.phtml';
        if (file_exists($content_layout_path)) {
            $this->content_layout = $content_layout_path;
        }

        // Set view params
        if (!empty($params)) {
            $this->view_params = $params;
        }

    }

    public function render()
    {
        include $this->layout;
    }

    public function getViewParam($param_name)
    {
        $result = null;

        if (!empty($param_name)) {
            $result = $this->view_params[$param_name];
        }

        return $result;
    }

    public function getViewParams()
    {
        return $this->view_params;
    }

    public function setViewParam($param_name, $param_value)
    {
        if (!empty(trim($param_name))) {
            $this->view_params[$param_name] = $param_value;
        }
    }

    public function getContent()
    {
        return $this->content_layout;
    }

}