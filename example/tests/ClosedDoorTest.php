<?php
class ClosedDoorTest extends PHPUnit_Framework_TestCase
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
        $this->door = new Door(new ClosedDoorState);
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
    public function testIsClosed()
    {
        $this->assertTrue($this->door->isClosed());
    }

    /**
     * @covers Door::isLocked
     */
    public function testIsNotLocked()
    {
        $this->assertFalse($this->door->isLocked());
    }

    /**
     * @covers Door::open
     * @covers ClosedDoorState::open
     * @uses   Door::isOpen
     */
    public function testCanBeOpened()
    {
        $this->door->open();
        $this->assertTrue($this->door->isOpen());
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
     * @covers ClosedDoorState::lock
     * @uses   Door::isLocked
     */
    public function testCanBeLocked()
    {
        $this->door->lock();
        $this->assertTrue($this->door->isLocked());
    }

    /**
     * @covers Door::unlock
     * @covers AbstractDoorState::unlock
     * @expectedException IllegalStateTransitionException
     */
    public function testCannotBeUnlocked()
    {
        $this->door->unlock();
    }
}
