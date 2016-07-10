<?php

namespace Qp\Kernel\Redis;

/**
 * QP框架核心模块：Redis模块 - 配置模块 - 配置对象
 */
class ConfigObj
{
    /**
     * 连接名
     *
     * @var null|string
     */
    private $conn_name = null;

    /**
     * Redis主机名
     *
     * @var null|string
     */
    private $host = null;

    /**
     * 密码
     *
     * @var null|string
     */
    private $auth = null;

    /**
     * 默认的数据库号
     *
     * @var null|int
     */
    private $database = null;

    /**
     * 端口号
     *
     * @var null|int
     */
    private $port = null;

    /**
     * 前缀
     *
     * @var null|string
     */
    private $prefix = null;

    /**
     * 其他配置数据
     *
     * @var array
     */
    private $other_config = [];

    /**
     * 构造器
     *
     * @param   string  $conn_name      连接名
     * @param   string  $host           服务器地址
     * @param   string  $auth           连接密码
     * @param   int     $database       默认库
     * @param   int     $port           端口号
     * @param   string  $prefix         用户自定义前缀
     * @param   array   $other_config   其他配置数据
     */
    public function __construct($conn_name, $host, $auth, $database, $port, $prefix, array $other_config)
    {
        $this->conn_name = strval($conn_name);
        $this->host = strval($host);
        $this->auth = strval($auth);
        $this->database = intval($database);
        $this->port = intval($port);
        $this->prefix = strval($prefix);
        $this->other_config = $other_config;
    }

    /**
     * 获取连接名
     *
     * @return string
     */
    public function conn_name()
    {
        return $this->conn_name;
    }

    /**
     * 获取Redis连接地址
     *
     * @return  string
     */
    public function host()
    {
        return $this->host;
    }

    /**
     * 获取Redis连接密码
     *
     * @return  string
     */
    public function auth()
    {
        return $this->auth;
    }

    /**
     * 获取Redis默认的数据库号
     *
     * @return  string
     */
    public function database()
    {
        return $this->database;
    }

    /**
     * 获取Redis连接端口号
     *
     * @return  string
     */
    public function port()
    {
        return $this->port;
    }

    /**
     * 获取Redis键名前缀配置不包括'_PHCR'
     *
     * @return  string
     */
    public function prefix()
    {
        return $this->prefix;
    }

    /**
     * 获取配置的其他数据
     *
     * @return  array
     */
    public function otherConfig()
    {
        return $this->other_config;
    }
}
