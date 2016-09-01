<?php

namespace BeyondIT\Composer;

class OpenCartNaivePhpInstaller
{
    static $registry;

    public function __get($name)
    {
        return self::$registry->get($name);
    }

    public function install($file) {
        include($file);
    }
}