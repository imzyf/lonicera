<?php

namespace library\Container;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    public function set($bean, $value);
}
