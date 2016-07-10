<?php

namespace Qp\Kernel;

use Qp\Kernel\Config;

/**
 * QP框架核心模块：堆栈信息跟踪模块
 *
 * 为了准确追踪错误位置，QP提供通用输出堆栈信息的方法
 */
class StackTrace
{
    /**
     * 获取PHP函数调用堆栈的顶部信息
     *
     * @return  string
     */
    public static function getLastCode($template = "")
    {
        $array = debug_backtrace();
        unset($array[0]);

        $code = "";
        foreach ($array as $row) {
            if (isset($row['file']) && isset($row['line'])) {
                if ($template == "") {
                    $code = " in file '" . $row['file'] . "' on line " . $row['line'];
                } else {
                    $code = strtr($template, [
                        "{file}" => $row['file'],
                        "{line}" => $row['line'],
                    ]);
                }
                break;
            }
        }

        return $code;
    }

    /**
     * 获取PHP函数调用堆栈的顶部信息
     *
     * @param   int     $number     堆栈中的顶部顺序：0表示最顶级的堆栈信息
     * @return  string
     */
    public static function getCode($number, $template = "")
    {
        $number = intval($number);
        $array = debug_backtrace();

        for ($i = 0; $i < $number; $i ++) {
            unset($array[$i]);
        }

        $code = "";
        foreach ($array as $row) {
            if (isset($row['file']) && isset($row['line'])) {
                if ($template == "") {
                    $code = " in file '" . $row['file'] . "' on line " . $row['line'];
                } else {
                    $code = strtr($template, [
                        "{file}" => $row['file'],
                        "{line}" => $row['line'],
                    ]);
                }
                break;
            }
        }

        return $code;
    }
}
