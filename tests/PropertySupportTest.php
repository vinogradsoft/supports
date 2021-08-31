<?php

namespace Test;

use Vinograd\Support\Event\PropertyEvent;
use Vinograd\Support\Event\PropertyListener;
use Vinograd\Support\ListenerStorage;
use Vinograd\Support\PropertySupport;
use PHPUnit\Framework\TestCase;

class PropertySupportTest extends TestCase
{
    public function testConstruct()
    {
        $storage = new ListenerStorage();
        $support = new PropertySupport($storage);
        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storagePrototypeValue = $storagePrototype->getValue($support);

        self::assertEquals($storage, $storagePrototypeValue);
    }

    public function testAddPropertyChangeListener()
    {

        $support = new PropertySupport(new ListenerStorage());

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $support->addPropertyChangeListener($listener1, $type1 = 'listener1');
        $support->addPropertyChangeListener($listener2, $type2 = 'listener2');


        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);

        $listenersType1 = $storage->getBy($type1);
        $listenersType2 = $storage->getBy($type2);
        self::assertCount(1, $listenersType1);
        self::assertCount(1, $listenersType2);

        self::assertEquals($listenersType1[0], $listener1);
        self::assertEquals($listenersType2[0], $listener2);
    }

    public function testRemovePropertyChangeListener()
    {
        $support = new PropertySupport(new ListenerStorage());

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $support->addPropertyChangeListener($listener1, $type1 = 'listener1');
        $support->addPropertyChangeListener($listener2, $type2 = 'listener2');

        $support->removePropertyChangeListener($listener1, $type1);
        $support->removePropertyChangeListener($listener2, $type2);

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);
        $listeners = $storage->getListeners();
        self::assertCount(0, $listeners);
    }

    public function testFirePropertyEvent()
    {
        $support = new PropertySupport(new ListenerStorage());
        $propertyName1 = 'propertyName';
        $oldPropValue1 = 'oldPropType1';
        $newPropValue1 = 'newPropType1';

        $propertyName2 = 'propertyName2';
        $oldPropValue2 = 'oldPropType2';
        $newPropValue2 = 'newPropType2';

        $listener1 = $this->mockAssertListener([
            'old' => $oldPropValue1,
            'new' => $newPropValue1,
            'propertyName' => $propertyName1,
        ]);

        $listener2 = $this->mockAssertListener([
            'old' => $oldPropValue2,
            'new' => $newPropValue2,
            'propertyName' => $propertyName2,
        ]);

        $support->addPropertyChangeListener($listener1, $propertyName1);
        $support->addPropertyChangeListener($listener2, $propertyName2);

        $support->firePropertyEvent($this, $propertyName1, $oldPropValue1, $newPropValue1);
        $support->firePropertyEvent($this, $propertyName2, $oldPropValue2, $newPropValue2);
    }


    protected function mockAssertListener(array $params): PropertyListener
    {
        return new class($this, $params) implements PropertyListener {
            private $test;
            private $params;

            public function __construct(PropertySupportTest $test, array $params)
            {
                $this->test = $test;
                $this->params = $params;
            }

            public function propertyChanged(PropertyEvent $evt): void
            {
                $old = $this->params['old'];
                $new = $this->params['new'];
                $type = $this->params['propertyName'];

                if ($type === $evt->getType()) {
                    $this->test->assertTrue(TRUE);
                } else {
                    $this->test->fail();
                }

                if ($new === $evt->getNewProperty()) {
                    $this->test->assertTrue(TRUE);
                } else {
                    $this->test->fail();
                }

                if ($old === $evt->getOldProperty()) {
                    $this->test->assertTrue(TRUE);
                } else {
                    $this->test->fail();
                }
            }
        };
    }

    public function testFirePropertyEventEmptyListeners()
    {
        $support = new PropertySupport(new ListenerStorage());
        $listener1 = $this->mockNoAssertListener();
        $support->addPropertyChangeListener($listener1, 'propertyThatWillNotChange');
        $support->firePropertyEvent($this, 'otherProperty', 'old', 'new');
        $this->assertTrue(TRUE);
    }

    protected function mockNoAssertListener(): PropertyListener
    {
        return new class($this) implements PropertyListener {
            private $test;

            public function __construct(PropertySupportTest $test)
            {
                $this->test = $test;
            }

            public function propertyChanged(PropertyEvent $evt): void
            {
                $this->test->fail();
            }
        };
    }

    public function testClear()
    {
        $support = new PropertySupport(new ListenerStorage());

        $listener1 = new DummyPropertyListener();
        $listener2 = new DummyPropertyListener();

        $support->addPropertyChangeListener($listener1, $type1 = 'listener1');
        $support->addPropertyChangeListener($listener2, $type2 = 'listener2');
        $support->clear();

        $reflection = new \ReflectionObject($support);

        $storagePrototype = $reflection->getProperty('storage');
        $storagePrototype->setAccessible(true);
        $storage = $storagePrototype->getValue($support);
        $listeners = $storage->getListeners();
        self::assertCount(0, $listeners);
    }
}
