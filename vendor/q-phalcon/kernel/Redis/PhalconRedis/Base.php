<?php

namespace Qp\Kernel\Redis\PhalconRedis;

use Qp\Kernel\Redis\RedisConfig;

/**
 * QP框架核心模块：Redis模块 - PhalconRedis模块 - 基础模块的父类
 */
class Base
{
    /**
     * Redis连接集合
     * 其中的元素类型为：\Qp\Kernel\Redis\ConnectionObj
     *
     * @var null|array
     */
    protected static $conn_list = null;

    /**
     * Redis连接名的集合
     *
     * @var null|array
     */
    protected static $conn_name_list = null;

    /**
     * 初始化Redis连接集合
     */
    protected static function init()
    {
        foreach (RedisConfig::getList() as $conn_name => $config_obj) {
            self::$conn_list[$conn_name] = new ConnectionObj($config_obj);
        }
        self::$conn_name_list = array_keys(self::$conn_list);
    }

    /**
     * 获取Phalcon的Redis连接对象
     *
     * @param   string  $name                   连接名
     * @return  \Phalcon\Cache\Backend\Redis
     */
    protected static function getConnection($name)
    {
        /**
         * @var ConnectionObj   $obj
         */
        $obj = self::$conn_list[$name];

        return $obj->getConnection();
    }
}
