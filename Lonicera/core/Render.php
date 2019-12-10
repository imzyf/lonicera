<?php

namespace Lonicera\core;

/**
 * Render Interface.
 */
interface Render
{
    public function init();

    public function assign($key, $value);

    public function display($view = '');
}
