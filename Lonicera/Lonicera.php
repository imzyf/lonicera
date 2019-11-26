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
//        require_once _SYS_PATH.'core/Controller.php';
//        require_once _SYS_PATH.'core/Loader.php';
//        spl_autoload_register(['Loader', 'loadLibClass']);
        $this->route();
        $this->dispatch();
//        echo 'hello world';
    }

    public function route()
    {
        $this->route = new Route();
        $this->route->init();
    }

    public function dispatch()
    {
        echo '<pre>';
        print_r($this->route);
        exit;
        $controllerName = ucfirst($this->route->controller).'Controller';
        $actionName = $this->route->action.'Action';
        $group = $this->route->group;
        $className = "app\\{$group}\module\controller\\{$controllerName}";
        // $path = _APP . $this->route->group . DIRECTORY_SEPARATOR . 'module';
        // $path .= DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $controllerName . '.php';
        // require_once $path;
        // die($className);
        $methods = get_class_methods($className);
        if (!in_array($actionName, $methods, true)) {
            throw new \Exception(sprintf('Method %s->%s not exists', $controllerName, $actionName));
        }
        $handler = new $className();
        $reflectClass = new \ReflectionClass('Lonicera\core\Controller');
        $reflectedProperty = $reflectClass->getProperty('route');
        $reflectedProperty->setAccessible(true);
        $reflectedProperty->setValue($this->route);
        $handler->{$actionName}();
    }
}
