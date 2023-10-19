<?php
declare(strict_types=1);

namespace Vinograd\Support\Event;

abstract class AbstractEvent implements Event
{

    private $source;

    /**
     * @param $source
     */
    public function __construct($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

}
