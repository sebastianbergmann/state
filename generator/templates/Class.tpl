<?php
class ___CLASS___ implements ___INTERFACE___
{
    /**
     * @var ___INTERFACE___
     */
    private $state;

    public function __construct(___INTERFACE___ $state)
    {
        $this->setState($state);
    }
___METHODS___
    private function setState(___INTERFACE___ $state)
    {
        $this->state = $state;
    }
}
