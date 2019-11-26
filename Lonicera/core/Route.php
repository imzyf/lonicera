<?php

namespace Lonicera\core;

/**
 * 路由类
 * 对 path_info 中的信息进行解析，以数组的形式返回正确的路由
 * 形式1： https://域名或ip地址:端口/分组名/控制器名 / 方法名 / 参数1 / 参数2 /
 * 标准形式： https；//域名或ip地址：端口 / 分组名 : 控制器名 / 方法名 ？ 变量1=参数1 & 变量2=参数2.
 */
class Route
{
    // 分组名，或称 module
    public $group;
    // 控制器
    public $control;
    // 控制的中的方法
    public $action;
    // 传给 action 的参数
    public $param;

    public function __construct()
    {
        $v = [];
    }

    public function init()
    {
        $route = $this->getRequest();
        $this->group = $route['group'];
        $this->control = $route['control'];
        $this->action = $route['action'];
        !empty($route['param']) && $this->param = $route['param'];
    }

    public function getRequest()
    {
        $filter_param = ['<', '>', '"', "'", '%3c', '%3C', '%3e', '%3E', '%22', '%27'];
        $uri = str_replace($filter_param, '', $_SERVER['REQUEST_URI']);
        // 解析 url，返回一个关联数组，其中 path 中存放路径，若参数的形式为 ?a=1&b=1，则会被保存在 query 中。
        $path = parse_url($uri);
        // 取 index.php 后面的内容
        if (0 == strpos($path['path'], 'index.php')) {
            $urlR0 = $path['path'];
        } else {
            $urlR0 = substr($path['path'], strpos($path['path'], 'index.php') + strlen('index.php'));
        }
        // 移除左边 /
        $urlR = ltrim($urlR0, '/');

        // 如果无法使用 parse_url 堆进行处理，证明并非path_info 方式，对其进行传统方式的处理
//        if($urlR == ''){
//            $route = $this->paraseTradition();
//            return $route;
//        }

        // 拆分后成为 分组/控制器/方法
        $reqArr = explode('/', $urlR);
        // 处理带有空白的情况
        foreach ($reqArr as $key => $value) {
            if (empty($value)) {
                unset($reqArr[$key]);
            }
        }
        // 对缺少某些值的情况添加默认值
        $cnt = count($reqArr);
        if (empty($reqArr) || empty($reqArr[0])) {
            $cnt = 0;
        }
        switch ($cnt) {
            // 全部缺少
            case 0:
                $route['group'] = $GLOBALS['_config']['route']['defaultApp']; //函数外的变量在函数中使用需要添加_GLOBALS
                $route['control'] = $GLOBALS['_config']['route']['defaultController'];
                $route['action'] = $GLOBALS['_config']['route']['defaultAction'];
                break;

            // 缺少 action 及后内容
            case 1:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['control'] = $gc[1];
                    $route['action'] = $GLOBALS['_config']['defaultAction'];
                } else {
                    //缺少 group
                    $route['group'] = $GLOBALS['_config']['defaultApp'];
                    $route['control'] = $reqArr[0];
                    $route['action'] = $GLOBALS['_config']['defaultAction'];
                }
                break;

            // 完整 cnt 为 2 时，表示没有参数
            default:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['control'] = $gc[1];
                    $route['action'] = $reqArr[1];
                } else {
                    //缺少分组
                    $route['group'] = $GLOBALS['_config']['defaultApp'];
                    $route['control'] = $reqArr[0];
                    $route['action'] = $reqArr[1];
                }
                //处理 /a/1/b/2 形式的参数
                for ($i = 2; $i < $cnt; ++$i) {
                    $route['param'][$reqArr[$i]] = isset($reqArr[++$i]) ? $reqArr[$i] : '';
                }
                break;
        }
        // 处理query字符
        if (!empty($path['query'])) {
            parse_str($path['query'], $routeQ); //形式化处理并以数组形式存放
            if (empty($route['param'])) {
                $route['param'] = [];
            }
            $route['param'] += $routeQ;
        }

        return $route;
    }
}
