<?php
declare(strict_types=1);

namespace Vinograd\Support;

use SplObjectStorage;

class ContextPropertySupport
{
    /** @var SplObjectStorage|null */
    protected static $propertySupports = null;

    /**
     * @param SupportedPropertyListeners $component
     * @return PropertySupport
     */
    public static function getPropertySupport(SupportedPropertyListeners $component): PropertySupport
    {
        if (self::$propertySupports === null) {
            self::$propertySupports = new SplObjectStorage();
        }
        if (!self::$propertySupports->contains($component)) {
            self::$propertySupports[$component] = new PropertySupport(new ListenerStorage());
        }

        return self::$propertySupports[$component];
    }
    /**
     * @param SupportedPropertyListeners $component
     */
    public static function removePropertySupport(SupportedPropertyListeners $component): void
    {
        if (self::$propertySupports === null) {
            return;
        }
        if (!self::$propertySupports->contains($component)) {
            return;
        }
        self::$propertySupports[$component]->clear();
        self::$propertySupports->detach($component);
    }
}