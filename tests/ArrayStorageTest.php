<?php

namespace HughCube\Counter\Tests;

use HughCube\Counter\ArrayStorage;
use HughCube\Counter\StorageInterface;
use PHPUnit\Framework\TestCase;

class ArrayStorageTest extends TestCase
{
    /**
     * @return StorageInterface
     */
    protected function createStorage()
    {
        $storage = new ArrayStorage;

        return $storage;
    }

    public function testInstance()
    {
        $storage = $this->createStorage();

        $this->assertInstanceOf(StorageInterface::class, $storage);
    }

    public function testIncr()
    {
        $storage = $this->createStorage();

        $this->assertSame(1, $storage->incr(__FUNCTION__));
        $this->assertSame(1, $storage->get(__FUNCTION__));

        $this->assertSame(2, $storage->incr(__FUNCTION__, 1));
        $this->assertSame(2, $storage->get(__FUNCTION__));

        $this->assertSame(5, $storage->incr(__FUNCTION__, 3));
        $this->assertSame(5, $storage->get(__FUNCTION__));

        $this->assertSame(6, $storage->incr(__FUNCTION__));
        $this->assertSame(6, $storage->get(__FUNCTION__));
    }

    public function testDecr()
    {
        $storage = $this->createStorage();

        $this->assertSame(1, $storage->set(__FUNCTION__, 1));
        $this->assertSame(0, $storage->decr(__FUNCTION__));
        $this->assertSame(0, $storage->get(__FUNCTION__));

        $this->assertSame(-1, $storage->decr(__FUNCTION__));
        $this->assertSame(-1, $storage->get(__FUNCTION__));

        $this->assertSame(-2, $storage->decr(__FUNCTION__, 1));
        $this->assertSame(-2, $storage->get(__FUNCTION__));

        $this->assertSame(100, $storage->set(__FUNCTION__, 100));
        $this->assertSame(99, $storage->decr(__FUNCTION__, 1));
        $this->assertSame(99, $storage->get(__FUNCTION__));

        $this->assertSame(96, $storage->decr(__FUNCTION__, 3));
        $this->assertSame(96, $storage->get(__FUNCTION__));

        $this->assertSame(95, $storage->decr(__FUNCTION__));
        $this->assertSame(95, $storage->get(__FUNCTION__));
    }

    public function testSet()
    {
        $storage = $this->createStorage();

        $this->assertSame(1, $storage->set(__FUNCTION__, 1));
        $this->assertSame(1, $storage->get(__FUNCTION__));

        $this->assertSame(0, $storage->set(__FUNCTION__, 0));
        $this->assertSame(0, $storage->get(__FUNCTION__));

        $this->assertSame(2, $storage->set(__FUNCTION__, 2));
        $this->assertSame(2, $storage->get(__FUNCTION__));
    }

    public function testGet()
    {
        $storage = $this->createStorage();

        $this->assertSame(0, $storage->get(__FUNCTION__));

        $this->assertSame(1, $storage->set(__FUNCTION__, 1));
        $this->assertSame(1, $storage->get(__FUNCTION__));

        $this->assertSame(0, $storage->set(__FUNCTION__, 0));
        $this->assertSame(0, $storage->get(__FUNCTION__));
    }

    public function testGetMultiple()
    {
        $storage = $this->createStorage();

        $items = ['key1' => 1, 'key2' => 2, 'key3' => 3, 'key4' => 4];

        foreach($items as $key => $value){
            $this->assertSame($value, $storage->set($key, $value));
        }

        $this->assertSame($items, $storage->getMultiple(array_keys($items)));
    }

    public function testHas()
    {
        $storage = $this->createStorage();

        $this->assertSame(false, $storage->has(__FUNCTION__));
        $this->assertSame(1, $storage->set(__FUNCTION__, 1));
        $this->assertSame(true, $storage->has(__FUNCTION__));
    }
}
