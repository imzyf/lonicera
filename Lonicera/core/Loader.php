<?php

namespace Lonicera\core;

/**
 * Loader.
 */
class Loader
{
    // 加载类
    public static function loadLibClass($class)
    {
//        echo $class.'<br>';
        $classFile = _ROOT.$class.'.php';
        $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $classFile);
//        echo $classFile.'<br>';
        require_once $classFile;
    }
}
