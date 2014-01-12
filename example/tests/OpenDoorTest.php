<?php
class OpenDoorTest extends PHPUnit_Framework_TestCase
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
        $this->door = new Door(new OpenDoorState);
    }

    /**
     * @covers Door::isOpen
     */
    public function testIsOpen()
    {
        $this->assertTrue($this->door->isOpen());
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
    public function testIsNotLocked()
    {
        $this->assertFalse($this->door->isLocked());
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
     * @covers OpenDoorState::close
     * @uses   Door::isClosed
     */
    public function testCanBeClosed()
    {
        $this->door->close();
        $this->assertTrue($this->door->isClosed());
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
     * @covers AbstractDoorState::unlock
     * @expectedException IllegalStateTransitionException
     */
    public function testCannotBeUnlocked()
    {
        $this->door->unlock();
    }
}
