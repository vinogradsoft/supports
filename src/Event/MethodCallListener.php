<?php

namespace Vinograd\Support\Event;

interface MethodCallListener extends Listener
{
    /**
     * @param CallMethodEvent $evt
     * @param mixed|null $meta
     * @return mixed
     */
    public function methodCalled(CallMethodEvent $evt, $meta = null);
}