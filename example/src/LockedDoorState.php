<?php
class LockedDoorState extends AbstractDoorState
{
    /**
     * @return ClosedDoorState
     */
    public function unlock()
    {
        return new ClosedDoorState;
    }
}
