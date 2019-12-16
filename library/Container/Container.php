<?php

namespace library\Container;

class Container extends ContainerAccess implements ContainerInterface
{
    protected $inject = []; // 存放给 bean 注入的方法
    //
    protected $instance = []; //对象存储的数组

    public function get($id)
    {
        return $this->offsetGet($id);
    }

    public function has($id)
    {
        return $this->offsetExists($id);
    }

    public function set($bean, $value)
    {
        $this->offsetSet($bean, $value);
    }

    /**
     * 给容器管理的 bean 注入某个方法.
     *
     * @param $bean
     * @param $methodName
     * @param $methodBody
     */
    public function inject($bean, $methodName, $methodBody)
    {
        if (!isset($this->inject[$bean][$methodName])) {
            $this->inject[$bean][$methodName] = $methodBody;
        }
    }

    /**
     * 获取某个 bean 上的方法.
     *
     * @param $bean bean 的名字
     * @param $methodName
     *
     * @return mixed
     */
    public function getInjectMethod($bean, $methodName)
    {
        if (isset($this->inject[$bean][$methodName])) {
            return $this->inject[$bean][$methodName];
        }
    }

    public function callback($bean, $callback, $parameters = [])
    {
        $methods = get_class_methods($this->get($bean));
        if (in_array($callback, $methods, true)) {
            return call_user_func_array([$this->get($bean), $callback], $parameters);
        } else {
            $injectMethod = $this->getInjectMethod($bean, $callback);
            if (isset($injectMethod)) {
                return call_user_func($injectMethod, $parameters);
            } else {
                throw new \RuntimeException('%s not exist %s function', $bean, $callback);
            }
        }
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }

    public function __set($bean, $value)
    {
        $this->offsetSet($bean, $value);
    }
}
