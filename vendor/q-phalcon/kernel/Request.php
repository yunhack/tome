<?php

namespace Qp\Kernel;

use Qp\Kernel\Http\Request\QpBaseRequest as Base;
use Phalcon\Http\Request as PR;

/**
 * QP框架核心模块：Http模块 - 请求模块
 *
 * 该类提供了Http请求相关的方法，方便获取Http请求中的相关参数
 */
class Request
{
    /**
     * 获取Phalcon的Request请求对象
     *
     * @return  \Phalcon\Http\Request
     */
    public static function request()
    {
        return Base::getRequestObject();
    }

    /**
     * 获取所有请求参数，包括post和get
     *
     * @return  array
     */
    public static function param()
    {
        return Base::getAllParam();
    }

    /**
     * 获取post方式的参数
     *
     * @return  array
     */
    public static function postParam()
    {
        return Base::getPostParam();
    }

    /**
     * 获取非post方式的参数
     *
     * @return  array
     */
    public static function nonPostParam()
    {
        return Base::getNonPostParam();
    }

    /**
     * 获取客户端IP地址
     *
     * @return  string
     */
    public static function getIp()
    {
        return Base::getClientAddress();
    }
}
