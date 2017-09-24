<?php
class Register
{
    public static function autoload($class)
    {
        $autoload = str_replace('\\', '/', $class);
        require $autoload.'.php';
    }
}

spl_autoload_register(['Register', 'autoload']);

$obj = new \src\Autoload();
$obj->test();

