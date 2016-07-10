<?php

namespace Qp\Kernel\Http\Middleware;

/**
 * QP框架核心模块：Http模块 - 中间件模块
 */
class QpMiddleware
{
    /**
     * 中间件处理状态
     * 0.默认初始状态
     * 1.继续处理下一个中间件
     * 2.中间件校验不通过，终止程序
     * 3.中间件校验通过，继续处理下面的事务
     *
     * @var int
     */
    private static $handle_status = 0;

    /**
     * 响应字符串
     *
     * @var string
     */
    private static $response_message = "";

    /**
     * 响应状态码
     *
     * @var int
     */
    private static $response_status = 200;

    /**
     * 处理可能缺省的中间件
     *
     * @param   array   $middleware     配置的中间件数组
     * @param   string  $namespace      控制器所在的命名空间
     */
    public static function handleConfigMiddleware(&$middleware, $namespace)
    {
        if (! is_array($middleware)) {
            throw new \InvalidArgumentException("路由配置有误，'middleware'配置项必须是数组");
        }
        if ($middleware == []) {
            return;
        }

        $pos = strrpos($namespace, "\\");
        if ($pos !== false) {
            $namespace = substr($namespace,0,$pos);
        }

        foreach ($middleware as $key => $value) {
            if (! is_string($value)) {
                throw new \InvalidArgumentException("路由配置有误，'middleware'配置项中每个的值必须是字符串");
            }
            if (strpos($value, '\\') === false) {
                $middleware[$key] = $namespace . "\\Middlewares\\" . ucfirst($value) . "Middleware";
            }
        }
    }

    /**
     * 设置处理状态：处理下一个中间件
     */
    public static function next()
    {
        self::$handle_status = 1;
    }

    /**
     * 设置处理状态：中间件校验不通过，终止处理
     *
     * @param   string  $message    响应消息
     * @param   int     $status     响应状态码
     */
    public static function end($message = "", $status = 200)
    {
        self::$handle_status = 2;
        self::$response_message = $message;
        self::$response_status = $status;
    }

    /**
     * 处理中间件
     *
     * @throws  \ErrorException
     */
    public static function handleMiddleware()
    {
        if (self::$handle_status != 0) {
            throw new \ErrorException("非法的QP框架使用方法 - 仅仅只能处理一次中间件，并且由框架底层自动处理！");
        }
        foreach (\Qp\Kernel\Http\Router\QpRouter::getMatchedData()['middleware'] as $middleware) {
            self::$response_message = "";
            self::$response_status = 200;
            (new $middleware())->handle();
            if (self::$handle_status == 2) {
                \Qp\Kernel\Response::send(self::$response_message, self::$response_status);
            }
        }
        self::$handle_status = 3;
    }
}
