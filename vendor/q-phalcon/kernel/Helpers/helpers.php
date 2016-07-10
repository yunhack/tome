<?php

if (! function_exists('array_get')) {
    /**
     * 从数组中获取值，如果不存在，则返回指定的默认值
     *
     * @param   array   $arr        数组
     * @param   string  $key        Key
     * @param   mixed   $default    指定默认值，null
     * @return  mixed
     */
    function array_get(array $arr, $key, $default = null)
    {
        if (is_null($key)) {
            return $arr;
        }

        if (isset($arr[$key])) {
            return $arr[$key];
        }

        return $default;
    }
}

if (! function_exists('array_first')) {
    /**
     * 获取数组的第一个元素，如果数组为空，返回null
     *
     * @param   array   $needle     参数数组
     * @return  mixed
     */
    function array_first(array $needle)
    {
        $re = null;
        foreach ($needle as $v) {
            $re = $v;
            break;
        }
        return $re;
    }
}

if (! function_exists('array_first_key')) {
    /**
     * 获取数组的第一个元素的key，如果数组为空，返回null
     *
     * @param   array   $needle     参数数组
     * @return  mixed
     */
    function array_first_key(array $needle)
    {
        $re = null;
        foreach ($needle as $k => $v) {
            $re = $k;
            break;
        }
        return $re;
    }
}

if (! function_exists('dd')) {
    /**
     * 输出传入的数据，并结束请求
     *
     * @param   mixed   $var    要打印的值
     */
    function dd($var)
    {
        echo "<pre>";
        var_dump($var);
        exit;
    }
}

if (! function_exists('dump')) {
    /**
     * 输出传入的数据，带有Html格式的样式
     *
     * @param   mixed   $var    要打印的值
     */
    function dump($var)
    {
        echo "<pre>";
        var_dump($var);
    }
}