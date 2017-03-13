<?php
define('KITE_VERSION', '1.0');
define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
defined('KITE_PATH') or define('KITE_PATH', __DIR__ . DS);
define('CORE_PATH', KITE_PATH . 'core' . DS);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);
defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录
defined('CONF_EXT') or define('CONF_EXT', EXT); // 配置文件后缀
// 环境常量
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

// 载入Loader类
require CORE_PATH . 'Loader.php';
// 注册自动加载
\core\Loader::register();

// 注册错误和异常处理机制
\core\Error::register();

// 加载惯例配置文件
\core\Config::set(include KITE_PATH . 'convention' . EXT);

?>