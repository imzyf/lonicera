<?php

// 网站根路径
define('_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
// 系统目录
define('_SYS_PATH', _ROOT.'Lonicera'.DIRECTORY_SEPARATOR);
// 应用根目录
define('_APP', _ROOT.'app'.DIRECTORY_SEPARATOR);

define('_RUNTIME', _ROOT.'runtime'.DIRECTORY_SEPARATOR);

require _ROOT.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

require _SYS_PATH.'Lonicera.php';
require _SYS_PATH.'config.php';

$app = new Lonicera\Lonicera();
$app->run();
