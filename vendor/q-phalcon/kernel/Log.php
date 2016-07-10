<?php

namespace Qp\Kernel;

/**
 * QP框架核心模块：日志模块
 *
 * 该模块根据PSR-3规范，给用户提供标准的日志底层基础接口，用户可以继承该类，封装一个属于自己模块的专有日志类
 */
class Log extends Log\BaseLog
{

    /**
     * 记录 debug 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function debug($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('debug', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 info 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function info($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('info', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 notice 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function notice($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('notice', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 warning 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function warning($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('warning', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 error 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function error($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('error', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 critical 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function critical($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('critical', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 alert 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function alert($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('alert', $message, $data, $is_replace, $modular);
    }

    /**
     * 记录 emergency 级别的日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @param   string  $message    日志消息 - 占位符使用'{键名}'
     * @param   array   $data       数据 - 支持key-value形式的数组，用来替换$msg中的占位符
     * @param   bool    $is_replace 是否替换占位字符
     * @param   string  $modular    应用模块名
     * @return  bool
     */
    public static function emergency($message = '', array $data = [], $is_replace = false, $modular = 'unknown_module')
    {
        return parent::log('emergency', $message, $data, $is_replace, $modular);
    }
}
