<?php

namespace HughCube\Counter;

interface StorageInterface
{
    /**
     * 计数自增一
     *
     * @param string $key 计数的key
     * @param integer $value 自增的数
     * @return integer
     */
    public function incr($key, $value = 1);

    /**
     * 计数自减一
     *
     * @param string $key 计数的key
     * @param integer $value 自增的数
     * @return integer
     */
    public function decr($key, $value = 1);

    /**
     * 重置计数
     *
     * @param string $key 计数的key
     * @param integer $value 重置的数
     * @return integer
     */
    public function set($key, $value);

    /**
     * 获取计数
     *
     * @param string $key 计数的key
     * @return integer
     */
    public function get($key);

    /**
     * 获取多个计数
     *
     * @param string[] $keys 计数的keys
     * @return integer[]
     */
    public function getMultiple(array $keys);

    /**
     * 判断一个计数是否存在
     *
     * @param string $key 计数的key
     * @return boolean
     */
    public function has($key);
}
