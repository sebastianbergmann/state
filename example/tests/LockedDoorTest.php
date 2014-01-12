<?php
class LockedDoorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Door
     */
    private $door;

    /**
     * @covers Door::__construct
     * @covers Door::setState
     */
    protected function setUp()
    {
        $this->door = new Door(new LockedDoorState);
    }

    /**
     * @covers Door::isOpen
     */
    public function testIsNotOpen()
    {
        $this->assertFalse($this->door->isOpen());
    }

    /**
     * @covers Door::isClosed
     */
    public function testIsNotClosed()
    {
        $this->assertFalse($this->door->isClosed());
    }

    /**
     * @covers Door::isLocked
     */
    public function testIsLocked()
    {
        $this->assertTrue($this->door->isLocked());
    }

    /**
     * @covers Door::open
     * @covers AbstractDoorState::open
     * @expectedException IllegalStateTransitionException
     */
    public function testCannotBeOpened()
    {
        $this->door->open();
    }

    /**
     * @covers Door::close
     * @covers AbstractDoorState::close
     * @expectedException IllegalStateTransitionException
     */
    public function testCannotBeClosed()
    {
        $this->door->close();
    }

    /**
     * @covers Door::lock
     * @covers AbstractDoorState::lock
     * @expectedException IllegalStateTransitionException
     */
    public function testCannotBeLocked()
    {
        $this->door->lock();
    }

    /**
     * @covers Door::unlock
     * @covers LockedDoorState::unlock
     * @uses   Door::isClosed
     */
    public function testCanBeUnlocked()
    {
        $this->door->unlock();
        $this->assertTrue($this->door->isClosed());
    }
}
