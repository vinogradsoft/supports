<?php
declare(strict_types=1);

namespace Test;

use Vinograd\Support\Event\PropertyEvent;
use Vinograd\Support\Event\PropertyListener;

class DummyPropertyListener implements PropertyListener
{

    public function propertyChanged(PropertyEvent $evt): void
    {

    }
}