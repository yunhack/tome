<?php

namespace Qp\Kernel;

use Qp\Kernel\Http\Middleware\QpMiddleware as Base;

/**
 * QP框架核心模块：Http模块 - 中间件模块
 *
 * 中间件模块的核心已经由框架处理，涉及到目录结构和规范问题，请参考官方文档
 */
class Middleware
{
    /**
     * 设置处理状态：处理下一个中间件
     */
    public static function next()
    {
        Base::next();
    }

    /**
     * 设置处理状态：中间件校验不通过，终止处理
     *
     * @param   string  $message    响应消息
     * @param   int     $status     响应状态码
     */
    public static function end($message = "", $status = 200)
    {
        if (! is_string($message)) {
            $debug_msg = "响应内容必须是字符串" . \Qp\Kernel\StackTrace::getLastCode();
            throw new \InvalidArgumentException($debug_msg);
        }
        Base::end($message, $status);
    }
}
