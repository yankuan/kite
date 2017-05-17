<?php
namespace server;
class Loader
{
    public static function autoload($class)
    {
        if (!strpos($class, '\\')) {
            return false;
        }
        list($name, $class) = explode('\\', $class, 2);
        $filename = SERVER_PATH . 'server/' . str_replace('\\', DS, $class) . '.php';
        if (is_file($filename)) {
            include $filename;
        } else {
            return false;
        }
        return true;
    }
    public static function register()
    {
        spl_autoload_register('server\\Loader::autoload');
    }
    public static function parseName($name, $type = 0)
    {
        if ($type) {
            return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function ($match) {return strtoupper($match[1]);}, $name));
        } else {
            return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
        }
    }
}
