<?php

namespace Lonicera;

use Lonicera\core\Route;

/**
 * Bootstrap Class.
 */
class Lonicera
{
    private $route;

    public function run()
    {
        require_once _SYS_PATH.'core/Loader.php';
        spl_autoload_register(['Lonicera\core\Loader', 'loadLibClass']);
        $this->route();
        $this->dispatch();
    }

    public function route()
    {
        $this->route = new Route();
        $this->route->init();
    }

    public function dispatch()
    {
        $controlName = ucfirst($this->route->controller).'Controller';
        $actionName = $this->route->action.'Action';
        $group = $this->route->group;
        $className = "app\\{$group}\module\controller\\{$controlName}";

        // 改为使用 spl_autoload_register
//        $path = _APP.$group.DIRECTORY_SEPARATOR.'module';
//        $path .= DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.$controlName.'.php';
//        require_once $path;

        $methods = get_class_methods($className);
        if (!in_array($actionName, $methods, true)) {
            throw new \Exception(sprintf('Method %s->%s not exists', $actionName, $actionName));
        }

        // 实例化控制器
        $handler = new $className();
        $handler->param = $this->param; // TODO ??

        // 将 route 信息注入到父控制器
        $reflectClass = new \ReflectionClass('Lonicera\core\Controller');
        $reflectedProperty = $reflectClass->getProperty('route');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($this->route);
        $handler->{$actionName}();
    }
}
