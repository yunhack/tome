<?php

namespace Qp\Kernel;

use Qp\Kernel\Config;

/**
 * QP框架核心模块：异常处理模块
 *
 * 程序入口通过catch所有异常，凡是遇到异常的情况都将终止操作！因为这是一个良好的实践经验！
 */
class Exception
{
    /**
     * 捕捉异常的类型
     *
     * @var int
     */
    private $exception_type = E_USER_ERROR | E_USER_NOTICE | E_USER_WARNING | E_USER_DEPRECATED | E_WARNING | E_NOTICE;

    /**
     * 致命错误
     *
     * @var array
     */
    private $fatal_error = [
        E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE
    ];

    /**
     * 构造器
     */
    public function __construct()
    {
        error_reporting($this->exception_type);
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }

    /**
     * 异常处理：抛出错误级别的异常
     *
     * @param   int             $level      错误等级
     * @param   string          $message    错误消息
     * @param   string          $file       错误的文件名
     * @param   int             $line       错误的代码行数
     * @param   array           $context    错误堆栈消息
     * @throws  \ErrorException
     */
	public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() && $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * 处理异常
     *
     * @param   \Exception  $ex  异常对象
     * @throws  \ErrorException
     */
    public function handleException($ex)
    {
        if ($ex instanceof \Exception) {
            throw new \ErrorException($ex);
        }
    }

    /**
     * 最终错误：遇到严重的错误，直接终止程序
     */
    public function handleShutdown()
    {
        if (is_null($error = error_get_last()) || !$this->isFatal($error['type'])) {
            return;
        }

        if (Config::get("app.debug")) {
            echo "<pre>";
            echo "Exception : " . $error['message'] . "<br>";
            echo "Catch position: " . $error['file'] . " : " . $error['line'];
        } else {
            echo strval(Config::get("app.prod_tip"));
        }
    }

    /**
     * QP框架最外层捕捉到异常后的处理方式
     * @param $ex
     */
    public function fatalHandler($ex)
    {
        if (! $ex instanceof \Exception) {
            return;
        }

        if (Config::get("app.debug")) {
            echo "<pre>";
            echo "Exception : " . $ex->getMessage() . "<br>";
            echo "Catch position : " . $ex->getFile() . " : " . $ex->getLine() . "<br><br>";
            echo $ex->getTraceAsString();

        }else{
            echo strval(Config::get("app.prod_tip"));
        }
    }

    /**
     * 判断传入的参数是否是PHP的致命错误
     *
     * 返回true表示致命错误，false表示非致命错误
     *
     * @param	int		$type		Php Error Level
     * @return	bool
     */
    protected function isFatal($type)
    {
        return in_array($type, $this->fatal_error);
    }
}
