<?php

namespace Qp\Kernel\Database;

use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Db\Adapter\Pdo\Sqlite;
use Phalcon\Db\Adapter\Pdo\Oracle;

/**
 * QP框架核心模块：Database链接模块 - 结构体：数据连接对象
 */
class ConnectionObj
{
    /**
     * 数据库驱动类型
     *
     * @var string
     */
    private $driver = "";

    /**
     * 配置数据
     *
     * @var array
     */
    private $config = [];

    /**
     * 连接名
     *
     * @var string
     */
    private $connection_name = "";

    /**
     * 连接对象
     *
     * @var null|Mysql|Oracle|Postgresql|Sqlite
     */
    private $connection = null;

    /**
     * 构造器
     *
     * @param   string  $connection_name    连接名
     * @param   string  $driver             驱动类型
     * @param   array   $config             配置数据
     */
    public function __construct($connection_name, $driver, array $config)
    {
        $this->connection_name = $connection_name;
        $this->driver = $driver;
        $this->config = $config;
    }

    /**
     * 获取驱动类型
     *
     * @return  string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * 获取配置数据
     *
     * @return  array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * 根据数据库驱动类型，生成数据库连接对象
     *
     * @param   string  $driver     驱动类型
     * @param   array   $config     配置数据
     * @return  Mysql|Oracle|Postgresql|Sqlite
     * @throws  \ErrorException
     */
    public static function createConnectionObj($driver, array $config)
    {
        switch ($driver) {
            case 'mysql' :
                return new Mysql($config);
                break;
            case 'postgresql' :
                return new Postgresql($config);
                break;
            case 'sqlite' :
                return new Sqlite($config);
                break;
            case 'oracle' :
                return new Oracle($config);
                break;
            default :
                $debug_msg = "~/config/database.php文件配置错误！不支持的数据库驱动类型 - " . strval($driver);
                throw new \InvalidArgumentException($debug_msg);
        }
    }

    /**
     * 设置当然对象的连接
     *
     * @throws \ErrorException
     */
    private function setConnection()
    {
        switch ($this->driver) {
            case 'mysql' :
                $this->connection = new Mysql($this->config);
                break;
            case 'postgresql' :
                $this->connection = new Postgresql($this->config);
                break;
            case 'sqlite' :
                $this->connection = new Sqlite($this->config);
                break;
            case 'oracle' :
                $this->connection = new Oracle($this->config);
                break;
            default :
                $debug_msg = "~/config/database.php文件配置错误！不支持的数据库驱动类型 - " . strval($this->driver);
                throw new \InvalidArgumentException($debug_msg);
        }
    }

    /**
     * 获取当前对象的连接(连接到数据库)
     *
     * @return  Mysql|Oracle|Postgresql|Sqlite
     * @throws  \ErrorException
     */
    public function getConnection()
    {
        if ($this->connection === null) {
            $this->setConnection();
        }
        return $this->connection;
    }
}
