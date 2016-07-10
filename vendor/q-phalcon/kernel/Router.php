<?php

namespace Qp\Kernel;

use Qp\Kernel\Http\Router\QpRouter as Base;

/**
 * QP框架核心模块：Http模块 - 路由模块
 *
 * 该模块根据QP框架的路由规则来匹配用户请求地址，已经映射到的路由配置文件
 */
class Router
{
    /**
     * 前缀映射的命名空间集合
     *
     * @var array
     */
    private static $ns = [];

    /**
     * 模块设置的状态
     * 0.初始
     * 1.调过set方法
     * 2.调过modules方法
     *
     * @var int
     */
    private static $modules_status = 0;

    /**
     * 当前匹配到的路由前缀 - 如果使用modules指定模块路由时，子路由的前缀将和模块的前缀一样
     *
     * @var string
     */
    private static $prefix = "";

    /**
     * 设置模块
     *
     * @param   array   $routers_files    前缀映射路由文件的数组
     * @throws  \ErrorException
     */
    public static function modules(array $routers_files)
    {
        if (self::$modules_status == 1) {
            throw new \ErrorException("app/routers.php中，如果要使用'modules'方法，那只能将其放在其他方法之前！");
        }

        if (self::$modules_status == 2) {
            throw new \ErrorException("路由设置文件中，最多只能调一次'modules'方法");
        }

        foreach ($routers_files as $prefix => $file) {

            if ($prefix !== Base::getPrefix() || $prefix == "") {
                return;
            }

            $file = QP_ROOT_PATH . strval($file);

            if (! file_exists($file)) {
                $err_msg = "The file '" . str_replace(['\\','/'], DIRECTORY_SEPARATOR, $file) . "' is not found!";
                throw new \ErrorException($err_msg);
            }

            self::$ns[$prefix] = $file;

            self::$modules_status = 2;

            self::$prefix = $prefix;

            require_once $file;

            self::$prefix = "";
        }
    }

    /**
     * 设置路由
     *
     * @param array $router
     */
    public static function set(array $router)
    {
        if (self::$modules_status == 0) {
            self::$modules_status = 1;
        }

        if (Base::hasMatched()) {
            return;
        }

        // 匹配前缀
        $prefix = $prefix = strval(array_get($router, 'prefix', ''));
        if ($prefix === "") {
            $prefix = self::$prefix;
        }
        if ($prefix !== Base::getPrefix()) {
            return;
        }

        // 匹配控制器
        $controllers = array_get($router, 'controllers', []);
        if (!is_array($controllers) || empty($controllers)) {
            throw new \InvalidArgumentException("路由配置有误，'controllers'的值必须是数组");
        }
        $controller = strval(array_get($controllers, Base::getController(), ''));
        if (empty($controller)) {
            return;
        }

        // 处理命名空间
        $namespace = strval(array_get($router, 'namespace', ''));
        self::handleConfigNamespace($namespace, $prefix);

        // 获取中间件，延迟处理 - 需要先加载其他模块才能处理中间件
        $middleware = array_get($router, 'middleware', []);
        Http\Middleware\QpMiddleware::handleConfigMiddleware($middleware, $namespace);

        Base::saveRouterData([
            'namespace' => $namespace,
            'controller' => $controller,
            'middleware' => $middleware
        ]);

        Base::match();
    }

    /**
     * 处理可能缺省情况下的命名空间
     *
     * @param   string  $namespace  命名空间配置
     * @param   string  $prefix     当前读取到的路由前缀
     * @throws  \ErrorException
     */
    private static function handleConfigNamespace(&$namespace, $prefix)
    {
        if ($prefix == "" && $namespace == "") {
            $namespace = "App\\Controllers";
            return;
        }

        if ($namespace == "") {
            throw new \ErrorException("当指定路由前缀时，'namespace'不能为空");
        }
    }
}
