<?php

namespace Test;

use Vinograd\Support\ContextFunctionalitySupport;
use PHPUnit\Framework\TestCase;
use Vinograd\Support\SupportedFunctionalities;

class ContextFunctionalitySupportTest extends TestCase
{

    public function testGetGroupFunctionalitySupport()
    {
        $supportForGroup1 = ContextFunctionalitySupport::getGroupFunctionalitySupport($group1 = 'group1');
        $supportForGroup1Control = ContextFunctionalitySupport::getGroupFunctionalitySupport($group1);

        $supportForGroup2 = ContextFunctionalitySupport::getGroupFunctionalitySupport($group2 = 'group2');
        $supportForGroup2Control = ContextFunctionalitySupport::getGroupFunctionalitySupport($group2);

        self::assertSame($supportForGroup1, $supportForGroup1Control);
        self::assertNotSame($supportForGroup1, $supportForGroup2);
        self::assertNotSame($supportForGroup1, $supportForGroup2Control);
    }

    public function testRemoveGroupFunctionalitySupport()
    {
        $supportForGroup = ContextFunctionalitySupport::getGroupFunctionalitySupport($group1 = 'group1');

        ContextFunctionalitySupport::removeGroupFunctionalitySupport($group1);

        $supportForGroupControl = ContextFunctionalitySupport::getGroupFunctionalitySupport($group1);
        self::assertNotSame($supportForGroup, $supportForGroupControl);
    }

    public function testRemoveGroupFunctionalitySupportNotException()
    {
        $supportForGroup = ContextFunctionalitySupport::getGroupFunctionalitySupport($group1 = 'group1');
        try {
            ContextFunctionalitySupport::removeGroupFunctionalitySupport($group1);
            ContextFunctionalitySupport::removeGroupFunctionalitySupport($group1);
        } catch (\Exception | \TypeError $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function testGetFunctionalitySupport()
    {
        $supported1 = $this->getMockForAbstractClass(SupportedFunctionalities::class);
        $supportForSupported1 = ContextFunctionalitySupport::getFunctionalitySupport($supported1);
        $supportForSupported1Control = ContextFunctionalitySupport::getFunctionalitySupport($supported1);

        $supported2 = $this->getMockForAbstractClass(SupportedFunctionalities::class);
        $supportForSupported2 = ContextFunctionalitySupport::getFunctionalitySupport($supported2);
        $supportForSupported2Control = ContextFunctionalitySupport::getFunctionalitySupport($supported2);

        self::assertSame($supportForSupported1, $supportForSupported1Control);
        self::assertNotSame($supportForSupported1, $supportForSupported2);
        self::assertNotSame($supportForSupported1, $supportForSupported2Control);
    }

    public function testRemoveFunctionalitySupport()
    {
        $supported = $this->getMockForAbstractClass(SupportedFunctionalities::class);
        $supportForSupported = ContextFunctionalitySupport::getFunctionalitySupport($supported);

        ContextFunctionalitySupport::removeFunctionalitySupport($supported);

        $supportForSupportedControl = ContextFunctionalitySupport::getFunctionalitySupport($supported);
        self::assertNotSame($supportForSupported, $supportForSupportedControl);
    }

    public function testRemoveFunctionalitySupportNotException()
    {
        $supported = $this->getMockForAbstractClass(SupportedFunctionalities::class);
        try {
            ContextFunctionalitySupport::removeFunctionalitySupport($supported);
        } catch (\Exception | \TypeError $e) {
            $this->fail();
        }

        $supportForSupported = ContextFunctionalitySupport::getFunctionalitySupport($supported);
        try {
            ContextFunctionalitySupport::removeFunctionalitySupport($supported);
            ContextFunctionalitySupport::removeFunctionalitySupport($supported);
        } catch (\Exception | \TypeError $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    public function tearDown(): void
    {
        ContextFunctionalitySupport::reset();
    }
}
