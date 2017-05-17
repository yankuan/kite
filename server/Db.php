<?php
namespace server;
class Db
{
    //  数据库连接实例
    private static $instance = null;
    // 查询次数
    public static $queryTimes = 0;
    // 执行次数
    public static $executeTimes = 0;

    public static function connect()
    {
        if (empty(self::$instance)) {
            $options  = require 'Config.php';
            self::$instance = new db\connector\Mysql($options);
            APP_DEBUG && Log::record('[ DB ] INIT ' . $options['type'] . ':' . var_export($options, true), 'info');
        }
        return self::$instance;
    }
    public static function __callStatic($method, $params)
    {
        // 自动初始化数据库
        return call_user_func_array([self::connect(), $method], $params);
    }
}
