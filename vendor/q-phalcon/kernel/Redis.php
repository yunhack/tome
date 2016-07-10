<?php

namespace Qp\Kernel;

use Qp\Kernel\Redis\PhalconRedis\PhalconRedis as Base;

/**
 * QP框架核心模块：Redis模块 - PhalconRedis模块
 *
 * Redis是在实践生产中非常稳定和高效的缓存数据库，推荐使用
 * 此类使用了Phalcon支持的Redis对象，如果您要使用PHP扩展的原生Redis，请使用PhpRedis类
 */
class Redis
{
    /**
     * 获取Redis连接对象
     *
     * @param   string  $connection_name        连接名
     * @return  \Phalcon\Cache\Backend\Redis
     */
    public static function connection($connection_name)
    {
        return Base::connection($connection_name);
    }
}
