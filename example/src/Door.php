<?php
class Door
{
    /**
     * @var DoorState
     */
    private $state;

    public function __construct(DoorState $state)
    {
        $this->setState($state);
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function open()
    {
        $this->setState($this->state->open());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function close()
    {
        $this->setState($this->state->close());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function lock()
    {
        $this->setState($this->state->lock());
    }

    /**
     * @throws IllegalStateTransitionException
     */
    public function unlock()
    {
        $this->setState($this->state->unlock());
    }

    /**
     * @return bool
     */
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

    private function setState(DoorState $state)
    {
        $this->state = $state;
    }
}
