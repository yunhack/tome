<?php

namespace Qp\Kernel;

use Qp\Kernel\Config\BaseConfig;

/**
 * QP框架核心模块：配置模块
 *
 * 动态读取配置文件，并加入到全局配置数组中
 */
class Config
{
    /**
     * 通过指定的KEY，获取配置数据
     *
     * @param   string      $key    参数KEY(文件名.数组索引[.数组索引...])
     * @return  mixed               参数值
     */
    public static function get($key = '')
    {
        return BaseConfig::get($key);
    }
}
