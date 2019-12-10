<?php

namespace app\front\module\controller;

use app\model\User;

class IndexController extends \Lonicera\core\Controller
{
    // /index/index
    public function indexAction()
    {
        $user = new User();
        $user->name = 'Lee';
        $user->age = 20;
        $user->save();

        $this->assign('age', $user->age + 1);
        $this->display();
    }

    // /index/hi
    public function hiAction()
    {
        $db = $this->db();
        $sql = 'select * from user where age > :age and id > :id';
        $ret = $db->query($sql, ['age' => 10, 'id' => 1]);
        dd($ret);
    }

    // /index/createPo
    public function createPoAction()
    {
        $model = new \Lonicera\core\Model();
        $model->buildPO('user');

        echo "createPoAction\n";
    }
}
