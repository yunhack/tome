<?php

namespace Qp\Kernel\Http\Router;

use Phalcon\Http\Request;

/**
 * QP框架核心模块：Http模块 - 路由模块的基础模块
 */
class QpRouter extends BaseRouter
{
    /**
     * 获取请求的路由字符串
     *
     * @return string
     */
    public static function getRouterStr()
    {
        if (self::$router_url === null) {
            self::initRouterUrl();
        }
        return self::$router_url;
    }

    /**
     * 校验路由是否已经匹配成功
     *
     * @return  bool
     */
    public static function hasMatched()
    {
        return self::$is_matched;
    }

    /**
     * 获取请求路由的前缀
     *
     * @return  string
     */
    public static function getPrefix()
    {
        if (self::$prefix === null) {
            self::initRouterMemberVar();
        }

        return self::$prefix;
    }

    /**
     * 获取请求路由的控制器
     *
     * @return  string
     */
    public static function getController()
    {
        if (self::$controller === null) {
            self::initRouterMemberVar();
        }

        return self::$controller;
    }

    /**
     * 获取请求路由的Action方法
     *
     * @return  string
     */
    public static function getMethod()
    {
        if (self::$method === null) {
            self::initRouterMemberVar();
        }

        return self::$method;
    }

    /**
     * 设置请求路由和设置的路由已经匹配
     */
    public static function match()
    {
        self::$is_matched = true;
    }

    /**
     * 保存已经匹配到的路由数据
     *
     * @param   array   $router     已经匹配到的路由数据
     */
    public static function saveRouterData(array $router)
    {
        self::$matched_router_data = $router;
    }

    /**
     * 获取已经匹配到的路由数组 - 保证匹配到的话，按照Main类的处理顺序执行！
     *
     * @return  array
     */
    public static function getMatchedData()
    {
        return self::$matched_router_data;
    }
}
