<?php

namespace Ident\Test\Registry\Decorator;

use Ident\Exception\IdentExceptions;
use Ident\HasIdentity;
use Ident\Registry\Decorator\GuardedRegistry;
use Ident\Test\Stubs\Order;
use Ident\Test\Stubs\OrderId;
use Ident\Test\Stubs\Payment;
use Ident\Test\Stubs\PaymentId;

/**
 * Class GuardedRegistryTest
 */
class GuardedRegistryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var GuardedRegistry
     */
    protected $guardedRegistry;

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

        $this->guardedRegistry = new GuardedRegistry(
            $this->registryMock,
            function (HasIdentity $identity) {
                if (!$identity instanceof Order) {
                    throw IdentExceptions::typeNotAllowed();
                }
            }
        );

        $this->order = new Order(OrderId::create());
        $this->payment = new Payment(PaymentId::create());
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

        $this->guardedRegistry->add($this->payment);
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

        $this->guardedRegistry->add($this->order);
    }
}
