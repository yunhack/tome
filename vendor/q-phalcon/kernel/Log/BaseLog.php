<?php

namespace Qp\Kernel\Log;

use Qp\Kernel\Config;
use Phalcon\Logger\Adapter\File;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * QP框架核心模块：日志模块 - 父类
 */
class BaseLog
{
    /**
     * 日志等级集合 - 不允许颠倒顺序
     *
     * @var array
     */
    private static $log_level_allow = [
        "debug", "info", "notice", "warning", "error", "critical", "alert", "emergency"
    ];

    /**
     * 日志模式 - 0到8的整数，默认为null
     *
     * @var null|int
     */
    private static $log_mode = null;

    /**
     * 判断传入的日志等级是否可以记录
     * 允许记录返回true，否则返回false
     *
     * @param   string  $log_level  日志等级
     * @return  bool
     */
    public static function isLog($log_level)
    {
        if (self::$log_mode === null) {
            $log_mode_config = Config::get("app.log_mode");
            if (! is_int($log_mode_config) || $log_mode_config < 0 || $log_mode_config > 8) {
                throw new \InvalidArgumentException("日志模式必须是0到8之间的整数，请检查配置项:app.log_mode");
            }
            self::$log_mode = $log_mode_config;
        }

        if (self::$log_mode >= array_search($log_level, self::$log_level_allow)) {
            return true;
        }

        return false;
    }

    /**
     * 处理日志文件 - 返回日志文件名
     * 如果期间日志路径
     *
     * @param   string  $modular    应用模块名
     * @param   string  $log_level  日志等级
     * @return  string              返回文件名
     */
    public static function handle_log_file($modular, $log_level)
    {
        if (! is_string($modular)) {
            throw new \InvalidArgumentException("模块名必须是字符串格式");
        }

        $modular = str_replace(['/','\\'], '_', $modular);

        $dir_path = QP_LOG_PATH . $modular . '/';

        if (!file_exists($dir_path)) {
            mkdir($dir_path, 0777, true);
        }

        $file_path = $dir_path . date('Y-m-d') . '_' . $log_level . '.log';
        if($log_level === ''){
            $file_path = $dir_path . date('Y-m-d') . '.log';
        }

        return $file_path;
    }

    /**
     * 获取占位符被处理后的字符串
     *
     * @param   string  $message    含有占位符的字符串
     * @param   array   $context    占位数组
     * @return  string
     */
    private static function interpolate($message, array $context = [])
    {
        $replace = [];
        foreach ($context as $key => $val) {
            $replace['{' . $key . '}'] = $val;
        }

        return strtr($message, $replace);
    }

    /**
     * 底层日志记录方法
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $log_level  日志等级
     * @param   string  $msg        日志消息(占位符使用{键名})
     * @param   array   $data       数据(支持key-value形式的数组，用来替换$msg中的占位字符)
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    protected static function log($log_level, $msg = '', $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        if (! in_array($log_level, self::$log_level_allow)) {
            throw new \InvalidArgumentException("不支持的日志等级:" . $log_level);
        }

        if (! self::isLog($log_level)) {
            return false;
        }

        $file_path = self::handle_log_file($modular, $log_level);

        $log_time = date('Y-m-d H:i:s');
        $ip = \Qp\Kernel\Request::getIp();
        $router_url = \Qp\Kernel\Http\Router\QpRouter::getRouterStr();

        $prefix = "[$log_time] [$ip] [router : $router_url] ";

        if ($is_replace) {
            $msg = self::interpolate($msg, $data);
        } else {
            $msg = $msg . json_encode(['data'=>$data]);
        }

        $logger = new FileAdapter($file_path);
        $logger->setFormatter(new LineFormatter("%message%"));

        return (bool) $logger->log($prefix.$msg);
    }
}
