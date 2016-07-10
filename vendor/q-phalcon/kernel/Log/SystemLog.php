<?php

namespace Qp\Kernel\Log;

use Phalcon\Cli\Router;
use Qp\Kernel\Config;
use Qp\Kernel\Request;
use Phalcon\Logger\Adapter\File;
use Phalcon\Logger\Adapter\File as FileAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;

/**
 * QP框架核心模块：系统日志模块
 */
class SystemLog
{

    /**
     * 记录起始请求日志
     * 记录成功返回true，失败或没记录日志返回false
     *
     * @return  bool
     */
    public static function request_start_log()
    {
        if (! BaseLog::isLog('debug')) {
            return false;
        }

        if (! Config::get("app.request_start_log")) {
            return false;
        }

        $data = Request::nonPostParam();

        if (Config::get("app.request_log_post")) {
            $data = Request::param();
        }

        $file_path = BaseLog::handle_log_file('framework', 'debug');

        $log_time = date("Y-m-d H:i:s", QP_RUN_START);
        $ip = Request::getIp();
        $router_url = \Qp\Kernel\Http\Router\QpRouter::getRouterStr();

        $prefix = "[$log_time] [$ip] [router : $router_url] ";

        $msg = "【请求日志】" . json_encode(['data'=>$data]);

        $logger = new FileAdapter($file_path);
        $logger->setFormatter(new LineFormatter("%message%"));

        return (bool) $logger->log($prefix.$msg);
    }
}
