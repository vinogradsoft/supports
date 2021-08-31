<?php

namespace Vinograd\Support;

use SplObjectStorage;

class ContextFunctionalitySupport
{

    /** @var SplObjectStorage|null */
    protected static $functionalitySupports = null;

    /** @var array */
    protected static $functionalityForGroups = [];

    /**
     * @param string $keyGroup
     * @return FunctionalitySupport
     */
    public static function getGroupFunctionalitySupport(string $keyGroup): FunctionalitySupport
    {
        if (!array_key_exists($keyGroup, self::$functionalityForGroups)) {
            self::$functionalityForGroups[$keyGroup] = static::createGroupFunctionalitySupport();
        }

        return self::$functionalityForGroups[$keyGroup];
    }

    /**
     * @param string $keyGroup
     */
    public static function removeGroupFunctionalitySupport(string $keyGroup): void
    {
        if (!array_key_exists($keyGroup, self::$functionalityForGroups)) {
            return;
        }
        self::$functionalityForGroups[$keyGroup]->clear();
        unset(self::$functionalityForGroups[$keyGroup]);
    }

    /**
     * @param SupportedFunctionalities $component
     * @return FunctionalitySupport
     */
    public static function getFunctionalitySupport(SupportedFunctionalities $component): FunctionalitySupport
    {
        if (self::$functionalitySupports === null) {
            self::$functionalitySupports = new SplObjectStorage();
        }
        if (!self::$functionalitySupports->contains($component)) {
            self::$functionalitySupports[$component] = static::createFunctionalitySupport();
        }

        return self::$functionalitySupports[$component];
    }

    /**
     * @param SupportedFunctionalities $component
     */
    public static function removeFunctionalitySupport(SupportedFunctionalities $component): void
    {
        if (self::$functionalitySupports === null) {
            return;
        }
        if (!self::$functionalitySupports->contains($component)) {
            return;
        }
        self::$functionalitySupports[$component]->clear();
        self::$functionalitySupports->detach($component);
    }

    /**
     * @return FunctionalitySupport
     */
    protected static function createFunctionalitySupport(): FunctionalitySupport
    {
        return new FunctionalitySupport();
    }

    /**
     * @return FunctionalitySupport
     */
    protected static function createGroupFunctionalitySupport(): FunctionalitySupport
    {
        return new FunctionalitySupport();
    }

    public static function reset(): void
    {
        static::$functionalitySupports = null;
        static::$functionalityForGroups = [];
    }
}