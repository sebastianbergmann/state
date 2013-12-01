<?php
class Door implements DoorInterface
{
    /**
     * @var DoorInterface
     */
    private $state;

    public function __construct(DoorInterface $state)
    {
        $this->setState($state);
    }

    public function open()
    {
        $this->setState($this->state->open());
    }

    public function close()
    {
        $this->setState($this->state->close());
    }

    public function lock()
    {
        $this->setState($this->state->lock());
    }

    public function unlock()
    {
        $this->setState($this->state->unlock());
    }

    public function isOpen()
    {
        return $this->state instanceof OpenDoorState;
    }

    /**
     * @return bool
     */
    public function isClosed()
    {
        return $this->state instanceof ClosedDoorState;
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        return $this->state instanceof LockedDoorState;
    }

    private function setState(DoorInterface $state)
    {
        $this->state = $state;
    }
}