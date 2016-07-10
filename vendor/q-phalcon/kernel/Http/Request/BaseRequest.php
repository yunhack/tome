<?php

namespace Qp\Kernel\Http\Request;

use Phalcon\Http\Request;

/**
 * QP框架核心模块：Http模块 - 请求模块的基础父类模块
 */
class BaseRequest
{
    /**
     * Phalcon的Request对象
     *
     * @var null|Request
     */
    protected static $request = null;

    /**
     * 所有的请求参数
     *
     * @var	null|array
     */
    protected static $all_param = null;

    /**
     * Post方式请求参数
     *
     * @var null|array
     */
    protected static $post_param = null;

    /**
     * 非POST方式的请求参数
     *
     * @var null|array
     */
    protected static $non_post_param = null;

    /**
     * 客户端IP地址
     *
     * @var null|string
     */
    protected static $clientIp = null;

    /**
     * 初始化所有请求参数
     */
    protected static function initParam()
    {
        if (self::$request === null) {
            self::$request = new Request();
        }

        self::$all_param = self::$request->get();
        if (isset(self::$all_param['__QP_url'])) {
            unset(self::$all_param['__QP_url']);
        }

        self::$post_param = self::$request->getPost();

        self::$non_post_param = array_diff(self::$all_param, self::$post_param);
    }

    /**
     * 初始化客户端IP地址
     *
     * @return	string
     */
    public static function initClientIp()
    {
        if (self::$request === null) {
            self::$request = new Request();
        }

        $ip = self::$request->getClientAddress();

        if ($ip == "::1") {
            $ip = "127.0.0.1";
        }

        self::$clientIp = $ip;
    }
}
