<?php

namespace app\model;

use Lonicera\core\BaseModel;

// Eloquent ORM
class UserORM extends BaseModel
{
    protected $table = 'user';

    public $id;
    // 名字
    public $name;
    public $age;
}
