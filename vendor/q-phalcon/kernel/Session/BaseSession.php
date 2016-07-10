<?php

namespace Qp\Kernel\Session;

use Qp\Kernel\Config;
use Phalcon\Session\Adapter\Files;
use Phalcon\Session\Adapter\Redis;
use Qp\Kernel\Redis\RedisConfig;

/**
 * QP框架核心模块：Session模块 - 基础模块的父类
 */
class BaseSession
{
    /**
     * 支持的Session驱动类型
     *
     * @var array
     */
    private static $driver_allow = ['file', 'redis'];

    /**
     * 是否开启Session功能
     *
     * @var null|bool
     */
    protected static $open = null;

    /**
     * Session驱动类型
     *
     * @var null|string
     */
    protected static $driver = null;

    /**
     * Phalcon在Redis中Key的前缀
     *
     * @var string
     */
    protected static $phalcon_prefix = "_PHCR";

    /**
     * 完整的前缀
     *
     * @var null|string
     */
    protected static $all_prefix_key = null;

    /**
     * session对象
     *
     * @var null|Files|Redis
     */
    protected static $session = null;

    /**
     * Session使用的Redis连接名
     *
     * @var null|string
     */
    private static $conn_name = null;

    /**
     * Session使用的Redis配置数据
     *
     * @var null|array
     */
    private static $conn_data = null;

    /**
     * 初始化Session配置数据
     */
    protected static function init()
    {
        $open = boolval(Config::get('session.open'));

        $driver = strval(Config::get('session.driver'));
        if (! in_array($driver, self::$driver_allow)) {
            throw new \InvalidArgumentException("不允许的Session驱动方式:{$driver}");
        }

        self::$open = $open;
        self::$driver = $driver;
    }

    /**
     * 初始化Session对象
     */
    protected static function initSession()
    {
        switch (self::$driver) {
            case 'file' : // 使用Phalcon
                self::$session = new Files();
                break;
            case 'redis' :
                if (self::$conn_data === null) {
                    self::initRedisConfigData();
                }
                self::$session = new Redis(self::$conn_data);
                break;
            default :
                throw new \InvalidArgumentException("不支持的Session驱动方式，请正确使用配置项'session.driver'");
        }
    }

    /**
     * 初始化关于Session的Redis配置
     *
     * @return  array
     */
    protected static function initRedisConfigData()
    {
        $conn_name = array_first_key(RedisConfig::getList());
        $conn_obj = RedisConfig::getConfig($conn_name);
        $config_data = $conn_obj->otherConfig();
        $config_data['host'] = $conn_obj->host();
        $config_data['port'] = $conn_obj->port();
        $auth = strval($conn_obj->auth());
        if (! empty($auth)) {
            $config_data['auth'] = $auth;
        }
        $config_data['persistent'] = true;
        $config_data['index'] = $conn_obj->database();
        $config_data['prefix'] = $conn_obj->prefix();
        $config_data['lifetime'] = self::getExpire();

        self::$all_prefix_key = self::$phalcon_prefix . $config_data['prefix'];
        self::$conn_name = $conn_name;
        self::$conn_data = $config_data;

        return $config_data;
    }

    /**
     * 获取过期时间配置
     *
     * @return  int
     */
    private static function getExpire()
    {
        $lifetime = Config::get("session.lifetime");
        if (! is_int($lifetime)) {
            throw new \InvalidArgumentException("会话过期时间设置不正确，必须为数组！请正确使用配置项'session.lifetime'");
        }
        if ($lifetime <= 0) {
            return -1;
        }
        return $lifetime * 60;
    }
}
