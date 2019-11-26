<?php

// 核心配置文件
$_config = [
    'mode' => 'debug',                   // 应用模式，默认为调试模式
    'filter' => 'true',                  // 是否过滤 $_GET,$_POST,$_COOKIE,$_FILES
    'charSet' => 'utf-8',                // 设置网页编码
    'route' => [
        'defaultApp' => 'front',         // 设置默认分组
        'defaultController' => 'index',  // 设置默认控制器
        'defaultAction' => 'index',      // 设置默认动作
        'defaultService' => 'index',     // 设置默认模型
        'UrlControllerName' => 'c',      // 自定义控制器名称 index.php?c=index
        'UrlActionName' => 'a',          // 自定义方法名称   index.php?c=index&a=index
        'UrlGroupName' => 'g',           // 自定义分组名称
    ],
    'db' => [
        'dsn' => 'mysql:host=localhost;dbname=practice',
        'username' => 'root',
        'password' => '',
        'param' => [],
    ],
    'smtp' => [],
];
