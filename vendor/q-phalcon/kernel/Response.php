<?php

namespace Qp\Kernel;

use Qp\Kernel\Http\Response\QpResponse as Base;

/**
 * QP框架核心模块：Http模块 - 响应模块
 */
class Response
{
    public static function send($message, $status)
    {
        Base::send($message, $status);
    }

    public static function response()
    {
        return Base::getResponse();
    }
}
