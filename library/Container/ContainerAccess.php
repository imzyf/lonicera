<?php

namespace library\Container;

class ContainerAccess implements \ArrayAccess
{
    private $keys = [];

    public function __construct(array $values = [])
    {
    }

    public function offsetExists($offset)
    {
        return isset($this->keys[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->keys[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->keys[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (isset($this->keys[$offset])) {
            unset($this->keys[$offset]);
        }
    }
}
