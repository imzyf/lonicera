<?php

namespace app\front\module\interceptor;

use Lonicera\core\InterceptorInterface;

class PayInterceptor implements InterceptorInterface
{
    /**
     * 前置拦截器，在所有的 action 运行前会进行拦截.
     */
    public function preHandle()
    {
        echo 'Pay Interceptor .. preHandle <br/>';

        return true;
    }

    /**
     * 后置拦截器.
     */
    public function postHandle()
    {
        echo 'Pay Interceptor .. postHandle <br/>';

        return true;
    }
}
