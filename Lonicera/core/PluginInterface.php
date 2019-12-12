<?php

namespace Lonicera\core;

interface PluginInterface
{
    public function init();

    public function run();

    public function destroy();
}
