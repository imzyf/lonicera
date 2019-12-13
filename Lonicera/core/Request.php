<?php

namespace Lonicera\core;

class Request
{
    public function getParam($param)
    {
        if (isset($_REQUEST[$param])) {
            // 进行安全处理
            return $_REQUEST[$param];
        } else {
            return null;
        }
    }

    public function getInt($param)
    {
        return intval($this->getParam($param));
    }
}
