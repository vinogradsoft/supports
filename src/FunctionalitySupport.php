<?php
declare(strict_types=1);

namespace Vinograd\Support;

use ArrayObject;
use BadMethodCallException;
use Vinograd\Support\Event\CallMethodEvent;
use Vinograd\Support\Event\MethodCallListener;
use Vinograd\Support\Exception\MethodAlreadyExistException;

class FunctionalitySupport
{
    /** @var array */
    protected $storage = [];

    /**
     * @param MethodCallListener $listener
     * @param string $methodName
     */
    public function installMethod(MethodCallListener $listener, string $methodName): void
    {
        if (array_key_exists($methodName, $this->storage)) {
            throw new MethodAlreadyExistException('This method ' . $methodName . '() already exists.');
        }
        $this->storage[$methodName] = $listener;
    }

    /**
     * @param string $methodName
     */
    public function uninstallMethod(string $methodName): void
    {
        unset($this->storage[$methodName]);
    }

    /**
     * @param $source
     * @param string $methodName
     * @param $arguments
     * @return mixed
     */
    public function fireCallMethodEvent($source, string $methodName, $arguments)
    {
        if (!isset($this->storage[$methodName])) {
            throw new BadMethodCallException('Calling unknown method ' . get_class($source) . '::' . $methodName . '(...))');
        }

        $evt = new CallMethodEvent($source, $methodName, $arguments);
        $support = $this->storage[$methodName];
        return $support->methodCalled($evt);
    }

    /**
     * @param string $methodName
     * @return bool
     */
    public function has(string $methodName): bool
    {
        return isset($this->storage[$methodName]);
    }

    /**
     * @param array $storage
     */
    public function setStorage(array $storage): void
    {
        $this->storage = $storage;
    }

    /**
     * @return array
     */
    public function copyStorage(): array
    {
        $copy = new ArrayObject($this->storage);
        return $copy->getArrayCopy();
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->storage = [];
    }
}