<?php

namespace Lonicera\core;

/**
 * 核心 路由类.
 *
 * 传统形式：index.php?c=index&a=read&id=1&name=Fan
 * PATH_INFO：index.php/index/read/id/1/name/Fan
 * eg:
 * /index.php?c=index&a=read&id=1&name=Fan
 * /index.php/index/read/id/1/name/Fan?id=1&name=Fan
 * /index/read/id/1/name/Fan?id=1&name=Fan
 * /front:index/read/id/1/name/Fan?id=1&name=Fan
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
        return $this->parsePathInfo();
    }

    // 解析传统形式的 URL
    private function parseTradition()
    {
        $route = [];
        $configRoute = $GLOBALS['_config']['route'];

        $route['control'] = $_GET[$configRoute['UrlControllerName']] ?? $configRoute['defaultController'];
        $route['action'] = $_GET[$configRoute['UrlActionName']] ?? $configRoute['defaultAction'];
        $route['group'] = $_GET[$configRoute['UrlGroupName']] ?? $configRoute['defaultApp'];

        // 剩下的为方法参数
        foreach (['UrlControllerName', 'UrlActionName', 'UrlGroupName'] as $configUrl) {
            unset($_GET[$configRoute[$configUrl]]);
        }
        $route['param'] = $_GET;

        return $route;
    }

    // 解析 PathInfo
    private function parsePathInfo()
    {
        $filter_param = ['<', '>', '"', "'", '%3c', '%3C', '%3e', '%3E', '%22', '%27'];
        $uri = str_replace($filter_param, '', $_SERVER['REQUEST_URI']);
        // 解析 url，返回一个关联数组，其中 path 中存放路径，若参数的形式为 ?a=1&b=1，a=1&b=1 则会被保存在 query 中。
        $path = parse_url($uri);

        // 取 index.php 后面的内容
        if (false === strpos($path['path'], 'index.php')) {
            // 不存在 index.php
            $urlR0 = $path['path'];
        } else {
            $urlR0 = substr($path['path'], strpos($path['path'], 'index.php') + strlen('index.php'));
        }
        // 移除左边 /
        $urlR = ltrim($urlR0, '/');

        // 如果无法使用 parse_url 堆进行处理，证明并非 path_info 方式，对其进行传统方式的处理
        if ('' == $urlR) {
            $route = $this->parseTradition();

            return $route;
        }

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

        $route = [];
        $route['group'] = $GLOBALS['_config']['route']['defaultApp']; // 函数外的变量在函数中使用需要添加_GLOBALS
        $route['control'] = $GLOBALS['_config']['route']['defaultController'];
        $route['action'] = $GLOBALS['_config']['route']['defaultAction'];

        switch ($cnt) {
            // 全部缺少
            case 0:
                break;

            // 缺少 action 及后内容
            case 1:
                if (stripos($reqArr[0], ':')) {
                    $gc = explode(':', $reqArr[0]);
                    $route['group'] = $gc[0];
                    $route['control'] = $gc[1];
                } else {
                    //缺少 group
                    $route['control'] = $reqArr[0];
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
                    // 缺少分组
                    $route['control'] = $reqArr[0];
                    $route['action'] = $reqArr[1];
                }
                //处理 /a/1/b/2 形式的参数
                for ($i = 2; $i < $cnt; ++$i) {
                    $route['param'][$reqArr[$i]] = isset($reqArr[++$i]) ? $reqArr[$i] : '';
                }
                break;
        }

        // 处理 query 字符
        if (!empty($path['query'])) {
            parse_str($path['query'], $routeQ); // 形式化处理并以数组形式存放
            if (empty($route['param'])) {
                $route['param'] = [];
            }
            $route['param'] += $routeQ;
        }

        return $route;
    }
}
