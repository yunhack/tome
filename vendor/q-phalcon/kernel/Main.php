<?php

namespace Qp\Kernel;

use Qp\Kernel\Http\Router\QpRouter as QR;
use Qp\Kernel\Session\QpSession as QS;

/**
 * QP框架的入口类：启动程序
 */
class Main
{
    /**
     * 启动程序
     */
    public function start()
    {
        // 1.定义QP框架需要的目录路径
        define('QP_ROOT_PATH' , dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR);
        define('QP_APP_PATH' , QP_ROOT_PATH . 'app' . DIRECTORY_SEPARATOR);
        define('QP_VIEW_PATH' , QP_ROOT_PATH . 'app_view' . DIRECTORY_SEPARATOR);
        define('QP_CONFIG_PATH', QP_ROOT_PATH . 'config' . DIRECTORY_SEPARATOR);
        define('QP_TMP_PATH', QP_ROOT_PATH . 'tmp' . DIRECTORY_SEPARATOR);

        // 2.加载常用函数到内存
        require_once "Helpers/helpers.php";

        // 3.加载QP自定义的异常模块 - 因为Phalcon的Exception在使用上有问题，因此需要重新实现
        $QpException = new Exception();

        try {

            // 4.加载配置文件 - /config目录下的php文件
            Config\BaseConfig::init(['app','database','session']);

            // 5.设置默认时区 - 从配置中读取
            date_default_timezone_set(Config::get("app.timezone"));

            // 6.定义日志目录路径 - 从配置中读取
            $this->setLogPath();

            // 7.记录请求日志
            Log\SystemLog::request_start_log();

            // 8.注册命名空间
            $this->setNamespace();

            // 9.加载路由模块
            $router = $this->handleRouter();

            // 10.定义Phalcon的DI
            $di = new \Phalcon\DI\FactoryDefault();

            // 11.预加载数据库链接
            $this->handleDBConnection($di);

            // 12.设置Redis数据库连接
            $this->handleRedis($di);

            // 13.设置会话 - 防止跨域攻击
            $this->handleSession($di);

            // 14.处理中间件
            $this->handleMiddleware();

            // 15.设置请求
            $this->setRequest($di, $router);

            // 16.开始请求，并处理响应
            $this->handleRequestAndEnd($di);

        } catch (\Exception $ex) {
            $QpException->fatalHandler($ex);
        }
    }

    /**
     * 定义日志目录
     *
     * @throws \ErrorException
     */
    private function setLogPath()
    {
        $config_log_dir = Config::get("app.log_dir");
        if (! is_string($config_log_dir)) {
            throw new \ErrorException("配置项app.log_dir必须是字符串格式");
        }

        $log_dir = QP_ROOT_PATH . str_replace(['/','\\'], DIRECTORY_SEPARATOR, $config_log_dir) . DIRECTORY_SEPARATOR;

        define('QP_LOG_PATH' , $log_dir);
    }

    /**
     * 注册命名空间：除了app目录外，还需要注册用户定义的命名空间
     */
    private function setNamespace()
    {
        $ns_config = (array) Config::get('app.namespace');

        $ns = ['App' => QP_APP_PATH];

        foreach ($ns_config as $key => $value) {
            if ($key == "App") {
                continue;
            }
            $ns[$key] = QP_ROOT_PATH . $value;
        }

        (new \Phalcon\Loader())->registerNamespaces($ns)->register();
    }

    /**
     * 处理用户请求的路由
     * 匹配失败直接抛出异常，成功则返回设置过的Phalcon路由对象
     *
     * @return  \Phalcon\Mvc\Router     Phalcon的路由对象
     * @throws  \ErrorException
     */
    private function handleRouter()
    {
        require_once QP_APP_PATH . "routers.php";

        if (QR::hasMatched() == false) {
            throw new \ErrorException("无法匹配到路由 : " . QR::getRouterStr());
        }

        $router = new \Phalcon\Mvc\Router();
        $matched_router_data = QR::getMatchedData();

        $router->setDefaults([
            "namespace" => $matched_router_data['namespace'],
            "controller" => $matched_router_data['controller'],
            "action" => QR::getMethod(),
        ]);

        return $router;
    }

    /**
     * 设置DI的数据库连接
     *
     * @param   \Phalcon\DI\FactoryDefault  $di     Phalcon的DI类
     */
    private function handleDBConnection(&$di)
    {
        foreach (Database\QpDB::getConnectionNameList() as $connection_name) {
            $di->set($connection_name, function () use ($connection_name) {
                return  DB::connection($connection_name);
            });
        }
    }

    /**
     * 预加载Redis数据库连接
     *
     * @param   \Phalcon\DI\FactoryDefault  $di     Phalcon的DI类
     */
    private function handleRedis(&$di)
    {
        foreach (Redis\PhalconRedis\PhalconRedis::getNameList() as $conn_name) {
            $di->set($conn_name, function () use ($conn_name) {
                return Redis\PhalconRedis\PhalconRedis::connection($conn_name);
            });
        }
    }

    /**
     * 启动Session并注入到DI
     *
     * @param   \Phalcon\DI\FactoryDefault  $di     Phalcon的DI类
     */
    private function handleSession(&$di)
    {
        if (! QS::isOpen()) {
            return;
        }

        QS::startSession();

        $di->set('session', function () {
            return QS::getSessionObject();
        });
    }

    /**
     * 处理中间件
     */
    private function handleMiddleware()
    {
        Http\Middleware\QpMiddleware::handleMiddleware();
    }

    /**
     * 设置请求和DI注入服务
     *
     * @param   \Phalcon\DI\FactoryDefault  $di     Phalcon的DI类
     */
    private function setRequest(&$di, &$router)
    {
        $di->set('router', $router);

        $di->set('url', function () {
            $url = new \Phalcon\Mvc\Url();
            $url->setBaseUri(QP_ROOT_PATH);
            return $url;
        });

        $di->set('view', function () {
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(QP_VIEW_PATH);
            return $view;
        });
    }

    /**
     * 终结请求：处理请求、会话、发送响应
     *
     * @param   \Phalcon\DI\FactoryDefault  $di     Phalcon的DI类
     */
    private function handleRequestAndEnd(&$di)
    {
        $response = Http\Response\QpResponse::getResponse();

        $response->setContent(
            (new \Phalcon\Mvc\Application($di))->handle()->getContent()
        );

        $response->send();
    }
}
