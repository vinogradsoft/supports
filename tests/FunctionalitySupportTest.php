<?php

namespace Test;

use BadMethodCallException;
use Vinograd\Support\Event\CallMethodEvent;
use Vinograd\Support\Event\MethodCallListener;
use Vinograd\Support\Exception\MethodAlreadyExistException;
use Vinograd\Support\FunctionalitySupport;

class FunctionalitySupportTest extends FunctionalitySupportCase
{
    public function testInstallMethod()
    {
        $support = new FunctionalitySupport();

        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $support->installMethod($listener2 = $this->mockMethodCallListener(), 'method2');

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);

        self::assertCount(2, $storage);
        self::assertArrayHasKey('method1', $storage);
        self::assertArrayHasKey('method2', $storage);
        self::assertEquals($storage['method1'], $listener1);
        self::assertEquals($storage['method2'], $listener2);
    }

    public function testInstallMethodMethodAlreadyExist()
    {
        $this->expectException(MethodAlreadyExistException::class);
        $support = new FunctionalitySupport();

        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $support->installMethod($listener2 = $this->mockMethodCallListener(), 'method1');
    }

    public function testUninstallMethod()
    {
        $support = new FunctionalitySupport();

        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $support->installMethod($listener2 = $this->mockMethodCallListener(), 'method2');

        $support->uninstallMethod('method1');
        $support->uninstallMethod('method2');

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);

        self::assertCount(0, $storage);
    }

    public function testHas()
    {
        $support = new FunctionalitySupport();

        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $support->installMethod($listener2 = $this->mockMethodCallListener(), 'method2');

        self::assertEquals(true, $support->has('method1'));
        self::assertEquals(true, $support->has('method2'));
        self::assertEquals(false, $support->has('method3'));
    }

    public function testSetStorage()
    {
        $support = new FunctionalitySupport();
        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');

        $support2 = new FunctionalitySupport();
        $support2->installMethod($listener2 = $this->mockMethodCallListener(), 'method2');

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage1 = $storagePrototype->getValue($support);

        $reflection2 = new \ReflectionObject($support);

        $storagePrototype2 = $reflection2->getProperty('storage');
        $storagePrototype2->setAccessible(true);
        $storage2 = $storagePrototype2->getValue($support2);

        $support->setStorage($storage2);
        $support2->setStorage($storage1);


        $checkReflection = new \ReflectionObject($support);

        $checkStoragePrototype = $checkReflection->getProperty('storage');
        $checkStoragePrototype->setAccessible(true);
        $checkStorage1 = $checkStoragePrototype->getValue($support);

        $checkReflection2 = new \ReflectionObject($support2);

        $checkStoragePrototype2 = $checkReflection2->getProperty('storage');
        $checkStoragePrototype2->setAccessible(true);
        $checkStorage2 = $checkStoragePrototype2->getValue($support2);

        self::assertEquals($storage1, $checkStorage2);
        self::assertEquals($storage2, $checkStorage1);
    }

    public function testCopyStorage()
    {
        $support = new FunctionalitySupport();
        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $copy = $support->copyStorage();
        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);

        self::assertSame($copy, $storage);
    }

    public function testClear()
    {
        $support = new FunctionalitySupport();
        $support->installMethod($listener1 = $this->mockMethodCallListener(), 'method1');
        $support->clear();

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);
        self::assertCount(0, $storage);
    }

    public function testFireCallMethodEvent()
    {
        $support = new FunctionalitySupport();
        $support->installMethod($listener1 = $this->mockAssertListener(
            [
                'arg' => 'argument',
                'method' => 'method1'
            ]
        ), 'method1');

        $support->fireCallMethodEvent($this, 'method1', ['argument']);
    }

    public function testFireCallMethodEventException()
    {
        $this->expectException(BadMethodCallException::class);
        $support = new FunctionalitySupport();
        $support->installMethod($this->mockMethodCallListener(), 'method1');

        $support->fireCallMethodEvent($this, 'method5', ['argument']);
    }
}
