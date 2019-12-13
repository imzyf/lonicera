<?php

namespace app\front\module\controller;

use app\model\User;

class IndexController extends \Lonicera\core\Controller
{
    public function _before_()
    {
        echo '_before_ function  <br/>';
    }

    public function _after_()
    {
        echo '_after_ function  <br/>';
    }

    // /index/index
    public function indexAction()
    {
        $user = new User();
        $user->name = 'Lee';
        $user->age = rand(12, 36);
        $user->save();

        $this->assign('age', $user->age + 1);
        $this->display();
    }

    // /index/hi?age=12
    public function hiAction()
    {
        $age = $this->request->getInt('age');
        dump($age);
        $db = $this->db();
        $sql = 'select * from user where age > :age and id > :id';
        $ret = $db->query($sql, ['age' => $age, 'id' => 1]);
        dump($ret);
    }

    // /index/createPo
    public function createPoAction()
    {
        $model = new \Lonicera\core\Model();
        $model->buildPO('user');

        echo "createPoAction\n";
    }
}
