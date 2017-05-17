<?php
use server\Input;
use server\Cache;
use server\Log;
function input()
{
    return input::get();
}
function cache($name, $value = '', $options = null)
{
    if (is_array($options)) {
        // 缓存操作的同时初始化
        Cache::connect($options);
    } elseif (is_array($name)) {
        // 缓存初始化
        return Cache::connect($name);
    }
    if ('' === $value) {
        // 获取缓存
        return Cache::get($name);
    } elseif (is_null($value)) {
        // 删除缓存
        return Cache::rm($name);
    } else {
        // 缓存数据
        if (is_array($options)) {
            $expire = isset($options['expire']) ? $options['expire'] : null; //修复查询缓存无法设置过期时间
        } else {
            $expire = is_numeric($options) ? $options : null; //默认快捷缓存设置过期时间
        }
        return Cache::set($name, $value, $expire);
    }
}
function writelog($log,$msg = '')
{
    $respon_list = require_once 'enum/Respond.php';
    if(!empty($msg)){
        Log::record($msg, 'error');
    }
    if  (!empty($log)&&!empty($respon_list[$log]))  {
        Log::record($respon_list[$log]['explain'], 'error');
    } else {
        Log::record('无法解析的错误< '.$log.' >!', 'error');
    }
    return "{'result':false,'info':$log}";
}
function page($page = 1, $num = 3)
{
    $result = [];
    $bind = [];
    $start = ($page-1)*$num;
    $end = $num;
    $result['page_string'] = " LIMIT ?, ?";
    $bind[] = $start;
    $bind[] = $end;
    $result['bind'] = $bind;
    return $result;
}
?>