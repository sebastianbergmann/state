<?php
interface DoorInterface
{
    public function open();
    public function close();
    public function lock();
    public function unlock();
}
