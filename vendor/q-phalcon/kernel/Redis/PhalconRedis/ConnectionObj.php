<?php

namespace Qp\Kernel\Redis\PhalconRedis;

use Qp\Kernel\Redis\ConfigObj;
use Phalcon\Cache\Backend\Redis;

/**
 * QP框架核心模块：Redis模块 - PhalconRedis模块 - 连接对象
 */
class ConnectionObj
{
    /**
     * Redis连接名
     *
     * @var null|string
     */
    private $conn_name = null;

    /**
     * Redis对象
     *
     * @var null|\Redis
     */
    private $connection = null;

    /**
     * 连接配置数据
     *
     * @var null|array
     */
    private $config_data = null;

    /**
     * 构造器
     *
     * @param   ConfigObj   $config_obj     连接配置对象
     */
    public function __construct(ConfigObj $config_obj)
    {
        $this->conn_name = $config_obj->conn_name();

        $config_data = $config_obj->otherConfig();
        $config_data['host'] = $config_obj->host();
        $config_data['port'] = $config_obj->port();
        $auth = strval($config_obj->auth());
        if (! empty($auth)) {
            $config_data['auth'] = $auth;
        }
        $config_data['persistent'] = true;
        $config_data['index'] = $config_obj->database();
        $config_data['prefix'] = $config_obj->prefix();

        $this->config_data = $config_data;
    }

    /**
     * 设置连接
     */
    private function setConnection()
    {
        $this->connection = new Redis(\Qp\Kernel\Redis\RedisConfig::getFrontCache(), $this->config_data);
    }

    /**
     * 获取Phalcon的Redis对象
     *
     * @return  Redis
     */
    public function getConnection()
    {
        if ($this->connection === null) {
            $this->setConnection();
        }
        return $this->connection;
    }
}
