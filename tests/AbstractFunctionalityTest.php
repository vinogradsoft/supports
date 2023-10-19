<?php
declare(strict_types=1);

namespace Test;

use Vinograd\Support\AbstractFunctionality;
use PHPUnit\Framework\TestCase;
use Vinograd\Support\Event\CallMethodEvent;
use Vinograd\Support\Exception\NotSupportedArgumentException;
use Vinograd\Support\Functionality;
use Vinograd\Support\SupportedFunctionalities;

class AbstractFunctionalityTest extends TestCase
{
    public function testMethodCalled()
    {
        $test = $this;
        $abstractFunctionality = $this->mockAbstractFunctionality([
            'checkArgument' => function ($method, $arguments) use ($test) {
                if (empty($arguments)) {
                    $test->fail();
                }
                if ($arguments[0] !== 'arg') {
                    $test->fail();
                }
                if (count($arguments) > 1) {
                    $test->fail();
                }
                return true;
            },
            'methodBody' => function (SupportedFunctionalities $component, $meta, $argument) use ($test) {
                $test->assertTrue(true);
                if ($argument !== 'arg') {
                    $test->fail();
                }
                return $meta;
            },
        ]);
        $stub = $this->getMockForAbstractClass(SupportedFunctionalities::class);

        $result = $abstractFunctionality->methodCalled(new CallMethodEvent($stub, 'assert', ['arg']), 'this meta any object');
        self::assertEquals('this meta any object', $result);
    }

    public function testNotSupportedArgumentAssertion()
    {
        $this->expectException(NotSupportedArgumentException::class);

        $abstractFunctionality = $this->mockAbstractFunctionality([
            'checkArgument' => function ($method, $arguments) {
                return false;
            },
        ]);

        $stub = $this->getMockForAbstractClass(SupportedFunctionalities::class);
        $abstractFunctionality->methodCalled(new CallMethodEvent($stub, 'noMethod', []));
    }

    public function testInstall()
    {
        $test = $this;

        $abstractFunctionality = $this->mockAbstractFunctionality([
            'installMethods' => function (SupportedFunctionalities $component) use ($test) {
                $test->assertTrue(true);
            },
        ]);

        $abstractFunctionality->install($this->getMockForAbstractClass(SupportedFunctionalities::class));
    }

    public function testUninstall()
    {
        $test = $this;

        $abstractFunctionality = $this->mockAbstractFunctionality([
            'uninstallMethods' => function (SupportedFunctionalities $component) use ($test) {
                $test->assertTrue(true);
            },
        ]);

        $abstractFunctionality->uninstall($this->getMockForAbstractClass(SupportedFunctionalities::class));
    }

    protected function mockAbstractFunctionality(array $params)
    {
        return new class($params) extends AbstractFunctionality {

            private $params;

            public function __construct(array $params)
            {
                $this->params = $params;
            }

            /**
             * @param SupportedFunctionalities $component
             * @param $meta
             * @param $argument
             * @return mixed
             */
            public function assert(SupportedFunctionalities $component, $meta, $argument)
            {
                $call = $this->params['methodBody'];
                return $call($component, $meta, $argument);
            }

            protected function assignMethod(SupportedFunctionalities $component, string $methodName): void
            {

            }

            protected function revokeMethod(SupportedFunctionalities $component, string $methodName): void
            {

            }

            protected function checkArguments($method, $arguments): bool
            {
                $call = $this->params['checkArgument'];
                return $call($method, $arguments);
            }

            protected function installMethods(SupportedFunctionalities $component): void
            {
                $call = $this->params['installMethods'];
                $call($component);
            }

            protected function uninstallMethods(SupportedFunctionalities $component): void
            {
                $call = $this->params['uninstallMethods'];
                $call($component);
            }

            public static function create(SupportedFunctionalities $component): Functionality
            {
                return new self([]);
            }
        };
    }


}
