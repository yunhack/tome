<?php

namespace Qp\Kernel\Http\Router;

use Qp\Kernel\Request;

/**
 * QP框架核心模块：Http模块 - 路由模块的基础父类模块
 */
class BaseRouter
{
    /**
     * 路由是否已经匹配成功
     *
     * @var bool
     */
    protected static $is_matched = false;

    /**
     * 请求的路由字符串
     *
     * @var null|string
     */
    protected static $router_url = null;

    /**
     * 路由：请求的路由前缀字符串
     *
     * @var null|string
     */
    protected static $prefix = null;

    /**
     * 路由：控制器
     *
     * @var string
     */
    protected static $controller = null;

    /**
     * 路由：Action方法
     *
     * @var string
     */
    protected static $method = null;

    /**
     * 匹配到的路由数据
     *
     * @var array
     */
    protected static $matched_router_data = [];

    /**
     * 初始化请求的路由字符串
     */
    protected static function initRouterUrl()
    {
        $os_type = strtoupper(PHP_OS);

        if (strpos($os_type, 'WIN') !== false) {
            $arr = Request::request()->get();
            self::$router_url = isset($arr['__QP_url']) ? $arr['__QP_url'] : '';
            return;
        }

        if (strpos($os_type, 'LINUX') !== false) {
            $uri = Request::request()->getURI();
            $pos = strpos($uri,'?');
            if ($pos !== false) {
                $uri = substr($uri,0,$pos);
            }
            $uri = str_replace("\\","/",$uri);
            while (true) {
                $tmp = str_replace("//","/",$uri);
                if ($tmp === $uri) {
                    break;
                }
                $uri = $tmp;
            }
            self::$router_url = $uri;
            return;
        }

        self::$router_url = "";
    }

    /**
     * 初始化路由成员变量：前缀、控制器、方法
     *
     * 路由构造器：以'/'分割
     * 0个元素 - index/index
     * 1个元素 - controller/index
     * 2个元素 - controller/method
     * 3个或3个以上的元素 - 最后一个表示method，倒数第二个表示controller，其余部分表示前缀
     */
    protected static function initRouterMemberVar()
    {
        if (self::$router_url === null) {
            self::initRouterUrl();
        }

        $router_str = trim(self::$router_url, "/");

        $arr = explode("/", $router_str);
        $arr_cnt = count($arr);

        self::$prefix = "";
        self::$controller = "";
        self::$method = "";

        switch ($arr_cnt) {
            case 1 :
                self::$method = "index";
                self::$controller = $arr[0];
                if($arr[0] == ""){
                    self::$controller = "index";
                }
                break;
            case 2 :
                self::$controller = $arr[0];
                self::$method = $arr[1];
                break;
            default :
                self::$method = $arr[$arr_cnt - 1];
                self::$controller = $arr[$arr_cnt - 2];
                unset($arr[$arr_cnt - 1],$arr[$arr_cnt - 2]);
                self::$prefix = implode('/',$arr);
                break;
        }
    }
}
