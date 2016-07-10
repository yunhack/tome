<?php

namespace Qp\Kernel\Redis\PhpRedis;

use Qp\Kernel\Redis\RedisConfig;

/**
 * QP框架核心模块：Redis模块 - PhpRedis模块 - 基础模块的父类
 */
class Base
{
    /**
     * PhpRedis连接集合
     * 其中的元素类型为：\Qp\Kernel\Redis\ConnectionObj
     *
     * @var null|array
     */
    protected static $conn_list = null;

    /**
     * PhpRedis连接名的集合
     *
     * @var null|array
     */
    protected static $conn_name_list = null;

    /**
     * 初始化PhpRedis连接集合
     */
    protected static function init()
    {
        foreach (RedisConfig::getList() as $conn_name => $config_obj) {
            self::$conn_list[$conn_name] = new ConnectionObj($config_obj);
        }
        self::$conn_name_list = array_keys(self::$conn_list);
    }

    /**
     * 获取Php的Redis连接对象
     *
     * @param   string  $name                   连接名
     * @return  \Redis
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
