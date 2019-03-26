<?php

namespace HughCube\Counter\Tests;

use HughCube\Counter\ArrayStorage;
use HughCube\Counter\Counter;
use HughCube\Counter\CounterInterface;
use HughCube\Counter\Exceptions\ExceptionInterface;
use HughCube\Counter\Exceptions\StorageException;
use PHPUnit\Framework\TestCase;

class CounterTest extends TestCase
{
    /**
     * @return Counter
     */
    protected function createPinCode()
    {
        $storage = new ArrayStorage();
        $counter = new Counter($storage, __CLASS__);

        return $counter;
    }

    public function testThrowException()
    {
        $exceptionMock = $this->getMock(ArrayStorage::class);

        $exceptionMock->expects($this->any())
            ->method('incr')
            ->will($this->throwException(new \Exception('incr')));

        $exceptionMock->expects($this->any())
            ->method('decr')
            ->will($this->throwException(new \Exception('decr')));

        $exceptionMock->expects($this->any())
            ->method('set')
            ->will($this->throwException(new \Exception('set')));

        $exceptionMock->expects($this->any())
            ->method('getMultiple')
            ->will($this->throwException(new \Exception('getMultiple')));

        $exceptionMock->expects($this->any())
            ->method('get')
            ->will($this->throwException(new \Exception('get')));

        $exceptionMock->expects($this->any())
            ->method('has')
            ->will($this->throwException(new \Exception('has')));


        /** @var Counter $counter */
        $counter = new Counter($exceptionMock, __CLASS__);

        try{
            $counter->get('test');
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }

        try{
            $counter->incr('test');
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }

        try{
            $counter->set('test', 1);
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }

        try{
            $counter->decr('test');
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }

        try{
            $counter->has('test');
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }

        try{
            $counter->getMultiple(['test']);
        }catch(StorageException $exception){
            $this->assertSame(true, $exception instanceof ExceptionInterface);
        }
    }

    public function testInstanceof()
    {
        $counter = $this->createPinCode();
        $this->assertInstanceOf(CounterInterface::class, $counter);
    }

    public function testIncr()
    {
        $counter = $this->createPinCode();

        $this->assertSame(1, $counter->incr(__CLASS__));
        $this->assertSame(1, $counter->get(__CLASS__));

        $this->assertSame(2, $counter->incr(__CLASS__, 1));
        $this->assertSame(2, $counter->get(__CLASS__));

        $this->assertSame(5, $counter->incr(__CLASS__, 3));
        $this->assertSame(5, $counter->get(__CLASS__));

        $this->assertSame(6, $counter->incr(__CLASS__));
        $this->assertSame(6, $counter->get(__CLASS__));
    }

    public function testDecr()
    {
        $counter = $this->createPinCode();

        $this->assertSame(1, $counter->set(__CLASS__, 1));
        $this->assertSame(0, $counter->decr(__CLASS__));
        $this->assertSame(0, $counter->get(__CLASS__));

        $this->assertSame(-1, $counter->decr(__CLASS__));
        $this->assertSame(-1, $counter->get(__CLASS__));

        $this->assertSame(-2, $counter->decr(__CLASS__, 1));
        $this->assertSame(-2, $counter->get(__CLASS__));

        $this->assertSame(100, $counter->set(__CLASS__, 100));
        $this->assertSame(99, $counter->decr(__CLASS__, 1));
        $this->assertSame(99, $counter->get(__CLASS__));

        $this->assertSame(96, $counter->decr(__CLASS__, 3));
        $this->assertSame(96, $counter->get(__CLASS__));

        $this->assertSame(95, $counter->decr(__CLASS__));
        $this->assertSame(95, $counter->get(__CLASS__));
    }

    public function testSet()
    {
        $counter = $this->createPinCode();

        $this->assertSame(1, $counter->set(__CLASS__, 1));
        $this->assertSame(1, $counter->get(__CLASS__));

        $this->assertSame(0, $counter->set(__CLASS__, 0));
        $this->assertSame(0, $counter->get(__CLASS__));

        $this->assertSame(2, $counter->set(__CLASS__, 2));
        $this->assertSame(2, $counter->get(__CLASS__));
    }

    public function testGet()
    {
        $counter = $this->createPinCode();

        $this->assertSame(1, $counter->set(__CLASS__, 1));
        $this->assertSame(1, $counter->get(__CLASS__));

        $this->assertSame(0, $counter->set(__CLASS__, 0));
        $this->assertSame(0, $counter->get(__CLASS__));
    }

    public function testGetMultiple()
    {
        $counter = $this->createPinCode();

        $items = ['key1' => 1, 'key2' => 2, 'key3' => 3, 'key4' => 4];

        foreach($items as $key => $value){
            $this->assertSame($value, $counter->set($key, $value));
        }

        $this->assertSame($items, $counter->getMultiple(array_keys($items)));
    }

    public function testHas()
    {
        $counter = $this->createPinCode();

        $this->assertSame(false, $counter->has(__CLASS__));
        $this->assertSame(1, $counter->set(__CLASS__, 1));
        $this->assertSame(true, $counter->has(__CLASS__));
    }
}
