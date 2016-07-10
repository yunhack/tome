<?php

namespace Qp\Kernel;

use Qp\Kernel\Redis\PhpRedis\PhpRedis as Base;

/**
 * QP框架核心模块：Http模块 - PhpRedis模块
 *
 * Redis是在实践生产中非常稳定和高效的缓存数据库，推荐使用
 * 此类使用了原生PHP支持的Redis对象，如果您要使用Phalcon的Redis对象，请使用PhalconRedis类
 */
class PhpRedis
{
    /**
     * 获取Redis连接对象
     *
     * @param   string  $connection_name        连接名
     * @return  \Redis
     */
    public static function connection($connection_name)
    {
        return Base::connection($connection_name);
    }
}
