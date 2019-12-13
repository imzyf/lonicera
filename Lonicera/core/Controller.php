<?php

namespace Lonicera\core;

use library\render;

/**
 * Base Controller.
 */
class Controller
{
    private $db;
    private $view;
    // 被反射注入
    protected static $route;
    protected $request;

    public function __construct()
    {
        $this->view = new render\PhpRender();
        $this->request = new Request();
    }

    protected function assign($key, $value)
    {
        $this->view->assign($key, $value);

        return $this->view;
    }

    public function db($config = [])
    {
        if (null == $config) {
            $config = $GLOBALS['_config']['db'];
        }
        $this->db = Db::getInstance($config);

        return $this->db;
    }

    public function display($file = '')
    {
        if (0 == func_num_args() || null == $file) {
            $controller = self::$route->controller;
            $action = self::$route->action;
            $viewFilePath = _ROOT.'app/'.self::$route->group.'/module/view/';
            $viewFilePath .= $controller.DIRECTORY_SEPARATOR.$action.'.php';
        } else {
            $viewFilePath = $file.'.php';
        }
        $this->view->display($viewFilePath);
    }
}
