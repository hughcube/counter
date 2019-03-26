<?php

namespace HughCube\Counter;

class ArrayStorage implements StorageInterface
{
    protected $data = [];

    /**
     * @inheritdoc
     */
    public function incr($key, $value = 1)
    {
        return $this->data[$key] = ($this->get($key) + $value);
    }

    /**
     * @inheritdoc
     */
    public function decr($key, $value = 1)
    {
        return $this->data[$key] = ($this->get($key) - $value);
    }

    /**
     * @inheritdoc
     */
    public function set($key, $value)
    {
        return $this->data[$key] = $value;
    }

    /**
     * @inheritdoc
     */
    public function getMultiple(array $keys)
    {
        $results = [];
        foreach($keys as $key){
            $results[$key] = $this->get($key);
        }

        return $results;
    }

    /**
     * @inheritdoc
     */
    public function get($key)
    {
        return intval($this->has($key) ? $this->data[$key] : 0);
    }

    /**
     * @inheritdoc
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }
}
