<?php

namespace HughCube\Counter;

use HughCube\Counter\Exceptions\Exception;
use HughCube\Counter\Exceptions\StorageException;
use Throwable;

class Counter implements CounterInterface
{
    /**
     * @var string
     */
    protected $keyPrefix = '';

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @param StorageInterface $storage 存储的实例
     * @param string $keyPrefix key前缀
     */
    public function __construct($storage, $keyPrefix = '')
    {
        $this->setStorage($storage);
        $this->setKeyPrefix($keyPrefix);
    }

    /**
     * @param string $keyPrefix
     */
    public function setKeyPrefix($keyPrefix)
    {
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param mixed $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function incr($key, $value = 1)
    {
        $key = $this->buildKey($key);

        try{
            return intval($this->storage->incr($key, $value));
        }catch(Throwable $exception){
            throw new StorageException('storage incr failed:' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function decr($key, $value = 1)
    {
        $key = $this->buildKey($key);

        try{
            return intval($this->storage->decr($key, $value));
        }catch(Throwable $exception){
            throw new StorageException('storage incr failed' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function set($key, $value)
    {
        $key = $this->buildKey($key);

        try{
            return intval($this->storage->set($key, $value));
        }catch(Throwable $exception){
            throw new StorageException('storage set failed' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function get($key)
    {
        $key = $this->buildKey($key);

        try{
            return intval($this->storage->get($key));
        }catch(Throwable $exception){
            throw new StorageException('storage get failed' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function getMultiple(array $keys)
    {
        if (empty($keys)){
            return [];
        }

        $keyMap = [];
        foreach($keys as $key){
            $keyMap[$key] = $this->buildKey($key);
        }

        try{
            $values = $this->storage->getMultiple(array_values($keyMap));
        }catch(Throwable $exception){
            throw new StorageException('storage getMultiple failed' . $exception->getMessage(), 0, $exception);
        }

        $results = [];
        foreach($keyMap as $key => $newKey){
            $results[$key] = 0;
            if (isset($values[$newKey])){
                $results[$key] = intval($values[$newKey]);
            }
        }

        return $results;
    }

    /**
     * @inheritdoc
     * @throws StorageException
     */
    public function has($key)
    {
        $key = $this->buildKey($key);

        try{
            return true == $this->storage->has($key);
        }catch(Throwable $exception){
            throw new StorageException('storage has failed' . $exception->getMessage(), 0, $exception);
        }
    }

    /**
     * 构建计数的key
     *
     * @param mixed $key 需要格式化的key
     * @return string
     */
    protected function buildKey($key)
    {
        /**
         * 如果不是string, 并且是digit, 转成string
         */
        if (!is_string($key) && is_numeric($key) && ctype_digit(strval($key))){
            $key = strval($key);
        }

        /**
         * getMultiple操作float类型的下标或直接被转成integer, 所以干脆就限制只能string
         */
        if (!is_string($key)){
            throw new Exception(sprintf('expects parameter $key to be string, %s given', gettype($key)));
        }

        $string = serialize([__CLASS__, $key]);

        return $this->keyPrefix . (md5($string) . '|' . crc32($string));
    }
}
