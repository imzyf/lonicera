<?php

//namespace app\module\front\controller;
use app\model\User;

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

    // /index/createPo
    public function createPoAction()
    {
        require_once _SYS_PATH.'core/DB.php';
        require_once _SYS_PATH.'core/Model.php';

        $model = new \Lonicera\core\Model();
        $model->buildPO('user');

        echo "createPoAction\n";
    }

    // /index/save
    public function saveAction()
    {
        require_once _SYS_PATH.'core/DB.php';
        require_once _SYS_PATH.'core/Model.php';
        require_once _APP.'model/User.php';
        $user = new User();
        $user->name = 'Lee';
//        $user->age = 19;
        $user->save();

        echo "saveAction\n";
    }
}
