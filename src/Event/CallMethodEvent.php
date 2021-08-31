<?php

namespace Vinograd\Support\Event;

class CallMethodEvent extends AbstractEvent
{
    private $method;

    /** @var array */
    private $args;

    public function __construct($source, $method, $args)
    {
        $this->method = $method;
        $this->args = $args;
        parent::__construct($source);
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return mixed
     */
    public function getArguments(): array
    {
        return $this->args;
    }

}