<?php

namespace Lonicera\core;

interface InterceptorInterface
{
    /**
     * 前置拦截器，在所有的 action 运行前会进行拦截.
     */
    public function preHandle();

    /**
     * 后置拦截器.
     */
    public function postHandle();
}
