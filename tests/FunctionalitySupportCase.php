<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use Vinograd\Support\Event\CallMethodEvent;
use Vinograd\Support\Event\MethodCallListener;

abstract class FunctionalitySupportCase extends TestCase
{
    /**
     * @return MethodCallListener
     */
    protected function mockMethodCallListener():MethodCallListener
    {
        return new class() implements MethodCallListener {
            public function methodCalled(CallMethodEvent $evt, $meta = null)
            {
            }
        };
    }

    /**
     * @param array $params
     * @return MethodCallListener
     */
    protected function mockAssertListener(array $params): MethodCallListener
    {
        return new class($this, $params) implements MethodCallListener {

            private $test;
            private $params;

            public function __construct(FunctionalitySupportTest $test, array $params)
            {
                $this->test = $test;
                $this->params = $params;
            }

            public function methodCalled(CallMethodEvent $evt, $meta = null)
            {
                $argument = $this->params['arg'];
                $method = $this->params['method'];

                if ($argument === $evt->getArguments()[0]) {
                    $this->test->assertTrue(TRUE);
                } else {
                    $this->test->fail();
                }
                if ($method === $evt->getMethod()) {
                    $this->test->assertTrue(TRUE);
                } else {
                    $this->test->fail();
                }
            }
        };
    }
}