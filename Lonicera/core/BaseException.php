<?php

namespace Lonicera\core;

class BaseException extends \Exception
{
    public function __toString()
    {
        return self::getMessage();
    }

    private function errMessage()
    {
        $err = date('Y-m-d H:i:s').'|';
        $err .= '异常信息：'.self::getMessage().'|';
        $err .= '异常码：'.self::getCode().'|';
        $err .= '堆栈回溯：'.json_encode(debug_backtrace()).PHP_EOL;

        return $err;
    }

    protected function _Log()
    {
        $err = self::errMessage();
        $tag = date('Ymd');
        file_put_contents(_RUNTIME."log-{$tag}.txt", $err, FILE_APPEND);
    }

    public function errorMessage()
    {
        self::_Log();

        echo self::errMessage();
    }
}
