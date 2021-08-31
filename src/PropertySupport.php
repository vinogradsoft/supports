<?php

namespace Vinograd\Support;

use Vinograd\Support\Event\PropertyEvent;
use Vinograd\Support\Event\PropertyListener;

class PropertySupport
{
    /** @var ListenerStorage */
    private $storage;

    /**
     * @param ListenerStorage $storage
     */
    public function __construct(ListenerStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param PropertyListener $listener
     * @param string $propertyName
     */
    public function addPropertyChangeListener(PropertyListener $listener, string $propertyName): void
    {
        $this->storage->add($listener, $propertyName);
    }

    /**
     * @param PropertyListener $listener
     * @param string $propertyName
     */
    public function removePropertyChangeListener(PropertyListener $listener, string $propertyName): void
    {
        $this->storage->remove($listener, $propertyName);
    }

    /**
     * @param $source
     * @param string $propName
     * @param $oldProp
     * @param $newProp
     */
    public function firePropertyEvent($source, string $propName, $oldProp, $newProp): void
    {
        $listeners = $this->storage->getBy($propName);
        if ($listeners === null) {
            return;
        }

        $evt = new PropertyEvent($source, $oldProp, $newProp, $propName);

        foreach ($listeners as $listener) {
            $listener->propertyChanged($evt);
        }
    }

    /**
     *
     */
    public function clear()
    {
        $this->storage->clear();
    }
}