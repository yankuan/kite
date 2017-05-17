<?php
namespace server;
use server\exception\ErrorException;
use server\exception\Handle;
use server\exception\ThrowableError;
class Error
{
    /**
     * 注册异常处理
     * @return void
     */
    public static function register()
    {
        error_reporting(E_ALL);
        set_error_handler([__CLASS__, 'appError']);
        set_exception_handler([__CLASS__, 'appException']);
        register_shutdown_function([__CLASS__, 'appShutdown']);
    }
    public static function appException($e)
    {
        if (!$e instanceof \Exception) {
            $e = new ThrowableError($e);
        }
        self::getExceptionHandler()->report($e);
        self::getExceptionHandler()->render($e)->send();
        
    }
    public static function appError($errno, $errstr, $errfile = '', $errline = 0, $errcontext = [])
    {
        $exception = new ErrorException($errno, $errstr, $errfile, $errline, $errcontext);
        if (error_reporting() & $errno) {
            // 将错误信息托管至 server\exception\ErrorException
            throw $exception;
        } else {
            self::getExceptionHandler()->report($exception);
        }
    }
    public static function appShutdown()
    {
        if (!is_null($error = error_get_last()) && self::isFatal($error['type'])) {
            // 将错误信息托管至server\ErrorException
            $exception = new ErrorException($error['type'], $error['message'], $error['file'], $error['line']);

            self::appException($exception);
        }

        // 写入日志
        Log::save();
    }
    protected static function isFatal($type)
    {
        return in_array($type, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE]);
    }
    public static function getExceptionHandler()
    {
        static $handle;
        if (!$handle) {
            // 异常处理handle
            $class = '';
            if ($class && class_exists($class) && is_subclass_of($class, "\\server\\exception\\Handle")) {
                $handle = new $class;
            } else {
                $handle = new Handle;
            }
        }
        return $handle;
    }
}
