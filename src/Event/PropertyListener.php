<?php

namespace Vinograd\Support\Event;

interface PropertyListener extends Listener
{
    public function propertyChanged(PropertyEvent $evt): void;
}