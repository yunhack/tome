<?php

namespace Qp\Kernel\Redis\PhpRedis;

use Qp\Kernel\Redis\ConfigObj;

/**
 * QP框架核心模块：Redis模块 - PhpRedis模块 - 连接对象
 */
class ConnectionObj
{
    /**
     * PhpRedis连接名
     *
     * @var null|string
     */
    private $conn_name = null;

    /**
     * 原生PHP的Redis对象
     *
     * @var null|\Redis
     */
    private $connection = null;

    /**
     * 原生Php的Redis连接配置对象
     *
     * @var null|array
     */
    private $config_object = null;

    /**
     * 构造器
     *
     * @param   ConfigObj   $config_obj     连接配置对象
     */
    public function __construct(ConfigObj $config_obj)
    {
        $this->conn_name = $config_obj->conn_name();
        $this->config_object = $config_obj;
    }

    /**
     * 设置连接
     */
    private function setConnection()
    {
        $this->connection = new \Redis();
        $this->connection->connect($this->config_object->host(), $this->config_object->port());
        $auth = $this->config_object->auth();
        if (! empty($auth)) {
            $this->connection->auth($auth);
        }
        $database = $this->config_object->database();
        if ($database != 0) {
            $this->connection->select($database);
        }
    }

    /**
     * 获取原生PHP的Redis对象
     *
     * @return  \Redis
     */
    public function getConnection()
    {
        if ($this->connection === null) {
            $this->setConnection();
        }
        return $this->connection;
    }
}
