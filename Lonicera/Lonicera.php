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
        require_once _SYS_PATH.'core/Route.php';
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
        $controlName = ucfirst($this->route->control).'Controller';
        $actionName = $this->route->action.'Action';
        $group = $this->route->group;
//        $className = "app\\{$group}\module\controller\\{$controlName}";

        $path = _APP.$group.DIRECTORY_SEPARATOR.'module';
        $path .= DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.$controlName.'.php';

        require_once $path;

        $methods = get_class_methods($controlName);
        if (!in_array($actionName, $methods, true)) {
            throw new \Exception(sprintf('Method %s->%s not exists', $actionName, $actionName));
        }

        $handler = new $controlName();
        $handler->param = $this->param; // TODO ??
//        $reflectClass = new \ReflectionClass('Lonicera\core\Controller');
//        $reflectedProperty = $reflectClass->getProperty('route');
//        $reflectedProperty->setAccessible(true);
//        $reflectedProperty->setValue($this->route);
        $handler->{$actionName}();
    }
}
