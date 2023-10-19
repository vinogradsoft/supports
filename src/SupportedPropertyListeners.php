<?php
declare(strict_types=1);

namespace Vinograd\Support;

use Vinograd\Support\Event\PropertyListener;

interface SupportedPropertyListeners extends Supportable
{
    /**
     * @param PropertyListener $listener
     * @param string $propertyName
     */
    public function addPropertyListener(PropertyListener $listener, string $propertyName): void;

    /**
     * @param PropertyListener $listener
     * @param string $propertyName
     */
    public function removePropertyListener(PropertyListener $listener, string $propertyName): void;
}