<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Vinograd\Support\ListenerStorage;

class ListenerStorageTest extends TestCase
{

    public function testAdd()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();
        $listener3 = new DummyPropertyListener();
        $listener4 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type1);
        $storage->add($listener3, $type1);

        $storage->add($listener1, $type2);
        $storage->add($listener2, $type2);
        $storage->add($listener3, $type2);
        $storage->add($listener4, $type2);

        $listenersT1 = $storage->getBy($type1);
        self::assertCount(3, $listenersT1);

        self::assertEquals($listenersT1[0], $listener1);
        self::assertEquals($listenersT1[1], $listener2);
        self::assertEquals($listenersT1[2], $listener3);


        $listenersT2 = $storage->getBy($type2);
        self::assertCount(4, $listenersT2);

        self::assertEquals($listenersT2[0], $listener1);
        self::assertEquals($listenersT2[1], $listener2);
        self::assertEquals($listenersT2[2], $listener3);
        self::assertEquals($listenersT2[3], $listener4);
    }

    public function testAddAlreadyExists()
    {
        $this->expectException(\InvalidArgumentException::class);

        $listener = new DummyPropertyListener();
        $type = 'SET_DRIVER';

        $storage = new ListenerStorage();
        $storage->add($listener, $type);
        $storage->add($listener, $type);
    }

    public function testRemove()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();
        $listener3 = new DummyPropertyListener();
        $listener4 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type1);
        $storage->add($listener3, $type1);

        $storage->add($listener1, $type2);
        $storage->add($listener2, $type2);
        $storage->add($listener3, $type2);
        $storage->add($listener4, $type2);

        $storage->remove($listener1, $type1);
        $storage->remove($listener2, $type1);
        $storage->remove($listener3, $type1);

        $listeners = $storage->getListeners();

        self::assertArrayNotHasKey($type1, $listeners);
        self::assertArrayHasKey($type2, $listeners);

        $storage->remove($listener1, $type2);
        $storage->remove($listener2, $type2);
        $storage->remove($listener3, $type2);
        $storage->remove($listener4, $type2);

        $listeners = $storage->getListeners();
        self::assertArrayNotHasKey($type2, $listeners);
        self::assertCount(0, $listeners);
    }

    public function testRemoveAssertion()
    {
        $this->expectException(\InvalidArgumentException::class);
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();


        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type1);

        $storage->add($listener1, $type2);
        $storage->add($listener2, $type2);
        $storage->remove($listener1, 'bugType');
    }

    public function testClear()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type1);

        $storage->add($listener1, $type2);
        $storage->add($listener2, $type2);

        $storage->clear();
        $listeners = $storage->getListeners();
        self::assertCount(0, $listeners);
    }

    public function testRemoveAnotherType()
    {
        $this->expectException(\InvalidArgumentException::class);

        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type2);
        $storage->remove($listener2, $type1);
    }

    public function testGetBy()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type2);
        $listener = $storage->getBy('fffff');
        self::assertEmpty($listener);

        $cool = $storage->getBy($type2);
        self::assertNotEmpty($cool);
    }

    public function testClearOf()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type2);
        $storage->clearOf($type1);

        $listener = $storage->getBy($type1);
        self::assertEmpty($listener);

        $cool = $storage->getBy($type2);
        self::assertNotEmpty($cool);

        $clearedListener = $storage->clearOf($type2);
        $notCool = $storage->getBy($type2);

        self::assertEmpty($notCool);
        self::assertNotEmpty($clearedListener);

        self::assertEquals($clearedListener[0], $listener2);
        self::assertCount(1, $clearedListener);

        $listeners = $storage->getListeners();
        self::assertArrayNotHasKey($type1, $listeners);
        self::assertArrayNotHasKey($type2, $listeners);
        self::assertCount(0, $listeners);
    }

    public function testGetListeners()
    {
        $storage = new ListenerStorage();

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();
        $listener3 = new DummyPropertyListener();
        $listener4 = new DummyPropertyListener();

        $type1 = 'SET_DRIVER';
        $type2 = 'SCAN_VISITOR';

        $storage->add($listener1, $type1);
        $storage->add($listener2, $type1);
        $storage->add($listener3, $type1);

        $storage->add($listener1, $type2);
        $storage->add($listener2, $type2);
        $storage->add($listener3, $type2);
        $storage->add($listener4, $type2);
        $listeners = $storage->getListeners();

        self::assertArrayHasKey($type1, $listeners);
        self::assertArrayHasKey($type2, $listeners);
        self::assertContains($listener1, $listeners[$type1]);
        self::assertContains($listener1, $listeners[$type2]);
        self::assertContains($listener4, $listeners[$type2]);
        self::assertNotContains($listener4, $listeners[$type1]);
    }
}
