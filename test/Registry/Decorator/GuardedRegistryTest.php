<?php

namespace Ident\Test\Registry\Decorator;

use Ident\Exception\IdentExceptions;
use Ident\HasIdentity;
use Ident\Registry\Decorator\GuardedRegistry;
use Ident\Test\AbstractIdentTest;
use Ident\Test\Stubs\Order;
use Ident\Test\Stubs\Payment;

/**
 * Class GuardedRegistryTest
 */
class GuardedRegistryTest extends AbstractIdentTest
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var GuardedRegistry
     */
    protected $registry;

    /**
     * @var Order
     */
    protected $order;

    /**
     * @var Payment
     */
    protected $payment;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->registryMock = $this->getMockBuilder('Ident\RegistersIdentities')
            ->getMockForAbstractClass();

        $this->registry = new GuardedRegistry(
            $this->registryMock,
            function (HasIdentity $identity) {
                if (!$identity instanceof Order) {
                    throw IdentExceptions::typeNotAllowed($identity);
                }
            }
        );

        $this->order = new Order();
        $this->payment = new Payment();

        $this->getProcessor()->processIdentities($this->order);
        $this->getProcessor()->processIdentities($this->payment);
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\TypeNotAllowed
     */
    public function shouldNotAllowInvalidTypes()
    {
        $this->registryMock
            ->expects($this->never())
            ->method('add')
            ->will($this->returnValue(null));

        $this->registry->add($this->payment);
    }

    /**
     * @test
     */
    public function shouldAllowValidTypes()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('add')
            ->with($this->order)
            ->will($this->returnValue(null));

        $this->registry->add($this->order);
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultRemove()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('remove')
            ->with($this->order)
            ->will($this->returnValue(null));

        $this->registry->remove($this->order);
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultClear()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('clear')
            ->will($this->returnValue(null));

        $this->registry->clear();
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultContains()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('contains')
            ->with($this->order)
            ->will($this->returnValue(true));

        $this->assertTrue($this->registry->contains($this->order));
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultGet()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('get')
            ->with($this->order->getIdentifier())
            ->will($this->returnValue($this->order));

        $this->assertSame(
            $this->order,
            $this->registry->get($this->order->getIdentifier())
        );
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultAll()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('all')
            ->will($this->returnValue('ALL'));

        $this->assertEquals(
            'ALL',
            $this->registry->all()
        );
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultMap()
    {
        $function = function ($a) {
            return $a;
        };

        $this->registryMock
            ->expects($this->once())
            ->method('map')
            ->with($function);

        $this->registry->map($function);
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultHas()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('has')
            ->with($this->order->getIdentifier())
            ->will($this->returnValue('HAS'));

        $this->assertEquals(
            'HAS',
            $this->registry->has($this->order->getIdentifier())
        );
    }

    /**
     * @test
     */
    public function shouldBehaveLikeDefaultDel()
    {
        $this->registryMock
            ->expects($this->once())
            ->method('del')
            ->with($this->order->getIdentifier());

        $this->registry->del($this->order->getIdentifier());
    }
}
