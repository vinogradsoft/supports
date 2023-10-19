<?php
declare(strict_types=1);

namespace Test;

use Vinograd\Support\ContextPropertySupport;
use PHPUnit\Framework\TestCase;
use Vinograd\Support\SupportedPropertyListeners;

class ContextPropertySupportTest extends TestCase
{

    public function testGetAndRemovePropertySupport()
    {
        $supported1 = $this->getMockForAbstractClass(SupportedPropertyListeners::class);
        $supported2 = $this->getMockForAbstractClass(SupportedPropertyListeners::class);
        $stub = $this->getMockForAbstractClass(SupportedPropertyListeners::class);

        try {
            ContextPropertySupport::removePropertySupport($stub);
        } catch (\Exception | \TypeError $e) {
            $this->fail();
        }

        $support1 = ContextPropertySupport::getPropertySupport($supported1);
        $support11 = ContextPropertySupport::getPropertySupport($supported1);

        $support2 = ContextPropertySupport::getPropertySupport($supported2);
        $support22 = ContextPropertySupport::getPropertySupport($supported2);

        self::assertSame($support1, $support11);
        self::assertNotSame($support1, $support2);
        self::assertNotSame($support1, $support22);

        ContextPropertySupport::removePropertySupport($supported1);
        ContextPropertySupport::removePropertySupport($supported2);

        $newSupport1 = ContextPropertySupport::getPropertySupport($supported1);
        $newSupport11 = ContextPropertySupport::getPropertySupport($supported1);

        $newSupport2 = ContextPropertySupport::getPropertySupport($supported2);
        $newSupport22 = ContextPropertySupport::getPropertySupport($supported2);

        self::assertNotSame($support1, $newSupport1);
        self::assertNotSame($support2, $newSupport2);

        self::assertSame($newSupport1, $newSupport11);
        self::assertNotSame($newSupport1, $newSupport2);
        self::assertNotSame($newSupport1, $newSupport22);

        try {
            ContextPropertySupport::removePropertySupport($stub);
        } catch (\Exception | \TypeError $e) {
            $this->fail();
        }
    }

}
