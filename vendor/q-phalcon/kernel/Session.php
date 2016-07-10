<?php

namespace Qp\Kernel;

use Qp\Kernel\Session\QpSession as Base;

/**
 * QP框架核心模块：Session模块
 *
 * 该模块提供Phalcon会话处理方法
 */
class Session
{
    /**
     * 获取Session中指定的值
     *
     * @param   string  $index          键
     * @param   mixed   $default_value  默认值
     * @return  mixed
     * @throws  \ErrorException
     */
    public static function get($index, $default_value = null)
    {
        Base::checkOpen();
        return Base::getSessionObject()->get($index, $default_value);
    }

    /**
     * 获取Session中所有得值
     * 注意：如果在此之前就已经使用过set或setBatch方法，该方法获取的值
     * 可能不是最新Session数据
     *
     * @return  mixed
     * @throws  \ErrorException
     */
    public static function getAll()
    {
        Base::checkOpen();
        $session = Base::getSessionObject();
        $session_id = $session->getId();
        $data_str = $session->read($session_id);
        return unserialize($data_str);
    }

    /**
     * 设置Session中指定的值
     *
     * @param   string  $index  键名
     * @param   mixed   $value  数据
     * @throws  \ErrorException
     */
    public static function set($index, $value)
    {
        Base::checkOpen();
        Base::getSessionObject()->set($index, $value);
    }

    /**
     * 批量设置Session的值
     *
     * @param   array   $data
     * @throws  \ErrorException
     */
    public static function setBatch(array $data)
    {
        Base::checkOpen();
        $session = Base::getSessionObject();
        foreach ($data as $index => $value) {
            $session->set($index, $value);
        }
    }

    /**
     * 关闭Session
     *
     * @return  bool|void
     * @throws  \ErrorException
     */
    public static function close()
    {
        Base::checkOpen();
        return Base::getSessionObject()->close();
    }

    /**
     * 获取SessionID
     *
     * @return  string
     * @throws  \ErrorException
     */
    public static function getId()
    {
        Base::checkOpen();
        return Base::getSessionObject()->getId();
    }

    /**
     * 获取Session过期时间
     *
     * @throws  \ErrorException
     */
    public static function getLifetime()
    {
        Base::checkOpen();
        return Base::getSessionObject()->getLifetime();
    }

    /**
     * 获取Session配置项
     *
     * @throws  \ErrorException
     */
    public static function getOptions()
    {
        Base::checkOpen();
        Base::getSessionObject()->getOptions();
    }

    /**
     * 判断Session中是否有指定的键
     *
     * @param   string  $index  键名
     * @return  bool
     * @throws  \ErrorException
     */
    public static function has($index)
    {
        Base::checkOpen();
        return Base::getSessionObject()->has($index);
    }

    /**
     * 注册会话ID
     *
     * @param   bool    $deleteOldSessionId     是否同时删除旧会话
     * @throws  \ErrorException
     */
    public static function regenerateId($deleteOldSessionId = true)
    {
        Base::checkOpen();
        Base::getSessionObject()->regenerateId($deleteOldSessionId);
    }

    /**
     * 指定会话ID
     *
     * @param   string  $session_id 新的会话ID
     * @throws  \ErrorException
     */
    public static function setId($session_id)
    {
        Base::checkOpen();
        Base::getSessionObject()->setId($session_id);
    }

    /**
     * 读取Session序列化形式的数据
     *
     * @return  mixed
     * @throws  \ErrorException
     */
    public static function read()
    {
        Base::checkOpen();
        return Base::getSessionObject()->read(self::getId());
    }

    /**
     * 从Session中移除指定的键
     *
     * @param   string  $index  键名
     * @throws  \ErrorException
     */
    public static function remove($index)
    {
        Base::checkOpen();
        Base::getSessionObject()->remove($index);
    }

    /**
     * 移除所有Session数据
     */
    public static function removeAll()
    {
        $data = self::getAll();
        if (! is_array($data)) {
            return;
        }
        $session = Base::getSessionObject();
        foreach ($data as $index => $value) {
            $session->remove($index);
        }
    }
}
