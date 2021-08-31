<?php

namespace Vinograd\Support;

use Vinograd\Support\Event\MethodCallListener;

interface Functionality extends MethodCallListener
{
    /**
     * @param SupportedFunctionalities $component
     * @return static
     */
    public static function create(SupportedFunctionalities $component): Functionality;

    /**
     * @param SupportedFunctionalities $component
     */
    public function install(SupportedFunctionalities $component): void;

    /**
     * @param SupportedFunctionalities $component
     */
    public function uninstall(SupportedFunctionalities $component): void;

}