<?php

namespace Qp\Kernel\Database;

/**
 * QP框架核心模块：Database链接模块 - 基础模块的父类
 */
class BaseDB
{
    /**
     * 连接对象列表
     * 数组中元素类型  ConnectionObj
     *
     * @var null|array
     */
    protected static $conn_list = null;

    /**
     * 连接对象名的集合
     *
     * @var null|array
     */
    protected static $conn_name_list = null;

    /**
     * 初始化连接对象的数据(准备连接数据)
     *
     * @throws  \ErrorException
     */
    protected static function initConnection()
    {
        $readConfig = new DBConfig();
        self::$conn_list = $readConfig->getAllConnection();
        self::$conn_list['db'] = $readConfig->getDefaultConnection();
        self::$conn_name_list = array_keys(self::$conn_list);
    }

    /**
     * 获取数据库连接静态对象
     *
     * @param   string  $connection_name    连接名
     * @return  \Phalcon\Db\Adapter\Pdo\Mysql|\Phalcon\Db\Adapter\Pdo\Oracle|\Phalcon\Db\Adapter\Pdo\Postgresql|\Phalcon\Db\Adapter\Pdo\Sqlite
     * @throws  \ErrorException
     */
    protected static function connect($connection_name)
    {
        /**
         * @var ConnectionObj   $obj
         */
        $obj = array_get(self::$conn_list, $connection_name);
        return $obj->getConnection();
    }
}
