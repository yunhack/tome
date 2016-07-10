<?php

namespace Qp\Kernel\Config;

/**
 * QP框架核心模块：配置模块 -> 基础模块
 */
class BaseConfig
{
    /**
     * 配置数组 - 动态数组
     *
     * @var array
     */
    protected static $settings = [];

    /**
     * 默认配置目录路径
     *
     * @var string
     */
    private static $default_dir = "";

    /**
     * 初始化配置
     *
     * @param   array   $init_config_files  需要初始化的文件名(不包括后缀)
     * @throws  \ErrorException
     */
    public static function init(array $init_config_files)
    {
        self::$settings = [];
        self::$default_dir = QP_CONFIG_PATH;

        foreach ($init_config_files as $filename) {
            self::addConfigFromFile(strval($filename));
        }
    }

    /**
     * 通过指定的KEY，获取配置数据
     *
     * @param   string  $key    参数KEY(文件名.数组索引[.数组索引...])
     * @return  null|mixed      参数值
     * @throws  \ErrorException
     */
    public static function get($key = '')
    {
        if (!is_string($key) || is_null($key)) {
            return null;
        }

        $arr = explode('.', $key);

        $config_file = $arr[0];
        if (!isset(self::$settings[$config_file])) {
            self::addConfigFromFile($config_file);
        }

        $config_key_first = isset($arr[1]) ? $arr[1] : '';
        if ($config_key_first === '') {
            return null;
        }

        $value = isset(self::$settings[$config_file]->$config_key_first)
            ? self::$settings[$config_file]->$config_key_first : null;

        if (is_null($value)) {
            return null;
        }

        for ($i = 2; $i < count($arr); $i++) {
            $config_key = $arr[$i];
            if ($config_key == '') {
                return null;
            }
            $value = isset($value->$config_key) ? $value->$config_key : null;
            if (is_null($value)) {
                return null;
            }
        }

        return $value;
    }

    /**
     * 读取文件，增加配置内容
     *
     * @param   string  $filename       文件名(不包括后缀)
     * @throws  \ErrorException
     */
    private static function addConfigFromFile($filename)
    {
        $file_path = self::$default_dir . $filename . ".php";

        if (!file_exists($file_path)) {
            $err_msg = "The file '" . str_replace(['\\','/'], DIRECTORY_SEPARATOR, $file_path) . "' is not found!";
            throw new \ErrorException($err_msg);
        }

        self::$settings[$filename] = new \Phalcon\Config\Adapter\Php($file_path);
    }
}
