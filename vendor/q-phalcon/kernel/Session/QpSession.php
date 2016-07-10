<?php

namespace Qp\Kernel\Session;

/**
 * QP框架核心模块：Session模块 - 基础模块
 */
class QpSession extends BaseSession
{
    /**
     * 是否开启Session功能
     *
     * @return  bool
     */
    public static function isOpen()
    {
        if (self::$open === null) {
            self::init();
        }
        return self::$open;
    }

    /**
     * 获取Session驱动类型
     *
     * @return  string
     */
    public static function driver()
    {
        if (self::$driver === null) {
            self::init();
        }
        return self::$driver;
    }

    /**
     * 获取完整的Session前缀
     *
     * @return  string
     */
    public static function getAllPrefixKey()
    {
        if (self::$all_prefix_key === null) {
            self::initRedisConfigData();
        }
        return self::$all_prefix_key;
    }

    /**
     * 获取Session对象
     *
     * @return  \Phalcon\Session\Adapter\Redis
     */
    public static function getSessionObject()
    {
        if (self::$session === null) {
            self::initSession();
        }

        return self::$session;
    }

    /**
     * 启动Session
     */
    public static function startSession()
    {
        self::checkOpen();

        if (self::$session === null) {
            self::initSession();
        }

        if (! self::$session->isStarted()) {
            self::$session->start();
        }
    }

    /**
     * 校验Session功能是否开启，如果没有开启，直接抛错
     *
     * @throws  \ErrorException
     */
    public static function checkOpen()
    {
        if (! self::isOpen()) {
            throw new \ErrorException("session功能已经关闭，如果需要开启，请修改配置项'session.open'");
        }
    }
}
