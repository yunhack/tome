<?php

namespace Qp\Kernel\Redis;

use Qp\Kernel\Config;

/**
 * QP框架核心模块：Redis模块 - 配置模块
 *
 * 该模块服务Redis和PhpRedis模块，配置数据共享
 */
class RedisConfig
{
    /**
     * 配置数组
     *
     * @var null|array
     */
    private static $config_list = null;

    /**
     * Phalcon的Frontend\Data对象
     *
     * @var null|\Phalcon\Cache\Frontend\Data
     */
    private static $frontCache = null;

    /**
     * Phalcon的Frontend\Data对象数据
     *
     * @var array
     */
    private static $frontCacheData = [
        "lifetime" => -1,
    ];

    /**
     * 获取Redis配置列表
     *
     * @return  array
     */
    public static function getList()
    {
        if (self::$config_list === null) {
            self::init();
        }
        return self::$config_list;
    }

    /**
     * 初始化Redis配置数据和前缓存结构
     */
    private static function init()
    {
        $config = Config::get("database.redis");
        if (empty($config)) {
            throw new \InvalidArgumentException("配置项'database.redis'不能为空");
        }
        $config = (array) $config;
        $db_conn_name_list = \Qp\Kernel\Database\QpDB::getConnectionNameList();

        foreach ($config as $name => $data) {
            $name = strval($name);
            if (in_array($name, $db_conn_name_list)) {
                throw new \InvalidArgumentException("配置项'database.redis'的连接名'{$name}'和其他类型的数据库连接名冲突");
            }

            $data = (array) $data;
            if (empty($data)) {
                throw new \InvalidArgumentException("配置项'database.redis.{$name}'必须为非空数组");
            }
            $conn_name = $name;
            self::handleConfigArr($data, 'host', $host);
            self::handleConfigArr($data, 'auth', $auth);
            self::handleConfigArr($data, 'database', $database);
            self::handleConfigArr($data, 'port', $port);
            self::handleConfigArr($data, 'prefix', $prefix);

            $prefix = strval($prefix);
            if ($prefix == "") {
                $data['prefix'] = ":";
            } else {
                $data['prefix'] = "_" . $prefix . ":";
            }

            self::$config_list[$conn_name] = new ConfigObj($conn_name, $host, $auth, $database, $port, $data['prefix'], $data);
        }

        if (self::$frontCache === null) {
            self::$frontCache = new \Phalcon\Cache\Frontend\Data(self::$frontCacheData);
        }
    }

    /**
     * 获取Phalcon的FrontendCache对象
     *
     * @return  \Phalcon\Cache\Frontend\Data
     */
    public static function getFrontCache()
    {
        if (self::$frontCache === null) {
            self::$frontCache = new \Phalcon\Cache\Frontend\Data(self::$frontCacheData);
        }
        return self::$frontCache;
    }

    /**
     * 获取指定的Redis配置对象
     *
     * @param   string      $name   Redis连接名
     * @return  ConfigObj
     */
    public static function getConfig($name)
    {
        if (self::$config_list === null) {
            self::init();
        }

        $name = strval($name);

        if (! isset(self::$config_list[$name])) {
            throw new \InvalidArgumentException("找不到连接名为'{$name}'的Redis配置！请检查配置项'database.redis'");
        }

        return self::$config_list[$name];
    }

    /**
     * 处理配置数组
     * 传入一个key，从数组中取出$value，并销毁对象
     *
     * @param   array   $data   引用，配置数组
     * @param   string  $key    键名
     * @param   mixed   $value  值
     */
    private static function handleConfigArr(&$data, $key, &$value)
    {
        $value = '';
        if (isset($data[$key])) {
            $value = $data[$key];
            unset($data[$key]);
        }
    }
}
