<?php

namespace Qp\Kernel;

use QP\Kernel\Database\QpDB as Base;

/**
 * QP框架核心模块：Database链接模块
 *
 * 该模块提供从用户的数据库配置文件中读取并建立数据库连接
 */
class DB
{
    /**
     * 获取默认的数据库匹配模式
     *
     * @return  int
     */
    public static function fetchMode()
    {
        return Base::getDefaultDBFetch();
    }

    /**
     * 获取链接对象
     *
     * @param   null $connection_name
     * @return \Phalcon\Db\Adapter\Pdo\Mysql|\Phalcon\Db\Adapter\Pdo\Oracle|\Phalcon\Db\Adapter\Pdo\Postgresql|\Phalcon\Db\Adapter\Pdo\Sqlite
     */
    public static function connection($connection_name = null)
    {
        return Base::getConnection($connection_name);
    }
}
