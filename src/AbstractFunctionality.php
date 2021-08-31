<?php

namespace Vinograd\Support;

use Vinograd\Support\Event\CallMethodEvent;
use Vinograd\Support\Exception\NotSupportedArgumentException;

abstract class AbstractFunctionality implements Functionality
{
    /**
     * @param CallMethodEvent $evt
     * @param mixed|null $meta
     * @return false|mixed
     */
    public function methodCalled(CallMethodEvent $evt, $meta = null)
    {
        $args = $evt->getArguments();
        $method = $evt->getMethod();

        if (!$this->checkArguments($method, $args)) {
            throw new NotSupportedArgumentException('Such arguments are not supported by the system.');
        }

        $source = $evt->getSource();
        array_unshift($args, $source, $meta);
        return \call_user_func_array([$this, $method], $args);
    }

    /**
     * @param SupportedFunctionalities $component
     * @param string $methodName
     */
    abstract protected function assignMethod(SupportedFunctionalities $component, string $methodName): void;

    /**
     * @param SupportedFunctionalities $component
     * @param string $methodName
     */
    abstract protected function revokeMethod(SupportedFunctionalities $component, string $methodName): void;

    /**
     * @param $method
     * @param $arguments
     * @return bool
     */
    abstract protected function checkArguments($method, $arguments): bool;

    /**
     * @param SupportedFunctionalities $component
     */
    abstract protected function installMethods(SupportedFunctionalities $component): void;

    /**
     * @param SupportedFunctionalities $component
     */
    abstract protected function uninstallMethods(SupportedFunctionalities $component): void;

    /**
     * @param SupportedFunctionalities $component
     */
    public function install(SupportedFunctionalities $component): void
    {
        $this->installMethods($component);
    }

    /**
     * @param SupportedFunctionalities $component
     */
    public function uninstall(SupportedFunctionalities $component): void
    {
        $this->uninstallMethods($component);
    }
}