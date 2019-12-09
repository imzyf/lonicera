<?php

//namespace app\module\front\controller;

class IndexController
{
    // /index/index
    public function indexAction()
    {
        echo "index action\n";
    }

    // /index/hi
    public function hiAction()
    {
        require_once _SYS_PATH.'core/DB.php';
        $db = \Lonicera\core\DB::getInstance($GLOBALS['_config']['db']);
        $sql = 'select * from user where age > :age and id > :id';
        $ret = $db->query($sql, ['age' => 10, 'id' => 1]);
        var_dump($ret);

        echo "hi action\n";
    }
}
