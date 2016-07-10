<?php

namespace Qp\Kernel\Http\Request;

use Phalcon\Http\Request;

/**
 * QP框架核心模块：Http模块 - 请求模块的基础模块，继承BaseRequest类
 */
class QpBaseRequest extends BaseRequest
{
    /**
     * 获取Phalcon的Request对象
     *
     * @return  Request
     */
    public static function getRequestObject()
    {
        if (self::$request === null) {
            self::$request = new Request();
        }
        return self::$request;
    }

    /**
     * 获取所有的请求参数
     *
     * @return  array
     */
    public static function getAllParam()
    {
        if (self::$all_param === null) {
            self::initParam();
        }
        return self::$all_param;
    }

    /**
     * 获取post方式的参数
     *
     * @return  array
     */
    public static function getPostParam()
    {
        if (self::$post_param === null) {
            self::initParam();
        }
        return self::$post_param;
    }

    /**
     * 获取非post方式的参数
     *
     * @return  array
     */
    public static function getNonPostParam()
    {
        if (self::$non_post_param === null) {
            self::initParam();
        }
        return self::$non_post_param;
    }

    /**
     * 获取客户端IP地址
     *
     * @return  string
     */
    public static function getClientAddress()
    {
        if (self::$clientIp === null) {
            self::initClientIp();
        }
        return self::$clientIp;
    }
}
