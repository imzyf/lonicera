<?php

namespace library\render;

use Lonicera\core\Render;

class PhpRender implements Render
{
    private $value = [];

    public function init()
    {
    }

    public function assign($key, $value)
    {
        $this->value[$key] = $value;
    }

    public function display($view = '')
    {
        extract($this->value);
        include $view;
    }

    public function fetch($file = '')
    {
        ob_start();
        ob_implicit_flush(0);
        $this->display($file);
        $content = ob_get_clean();

        return $content;
    }
}
