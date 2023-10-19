<?php
declare(strict_types=1);

namespace Vinograd\Support\Event;

interface Event
{
    public function getSource();
}