<?php

namespace Qp\Kernel\Http\Response;

/**
 * QP框架核心模块：Http模块 - 响应模块
 *
 * 该类实现了Http响应方法，方便用户自定义响应信息
 */
class QpResponse
{
    /**
     * Phalcon响应对象
     *
     * @var null|\Phalcon\Http\Response
     */
    private static $response = null;

    /**
     * 初始化响应对象
     */
    private static function init()
    {
        self::$response = new \Phalcon\Http\Response();
    }

    /**
     * 获取响应对象
     *
     * @return  \Phalcon\Http\Response
     */
    public static function getResponse()
    {
        if (self::$response === null) {
            self::init();
        }
        return self::$response;
    }

    /**
     * 发送响应消息
     *
     * @param   string  $message    响应信息
     * @param   int     $status     响应状态码
     */
    public static function send($message, $status)
    {
        if (self::$response === null) {
            self::init();
        }

        self::$response->setStatusCode(intval($status));
        self::$response->setContent(strval($message));
        self::$response->send();
    }
}
