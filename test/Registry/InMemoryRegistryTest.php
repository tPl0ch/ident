<?php

namespace Ident\Test\Registry;

use Ident\Registry\InMemoryRegistry;
use Ident\Test\AbstractIdentTest;
use Ident\Test\Stubs\Order;
use Ident\Test\Stubs\Payment;

/**
 * Class InMemoryRegistryTest
 */
class InMemoryRegistryTest extends AbstractIdentTest
{
    /**
     * @var \Ident\Registry\InMemoryRegistry
     */
    protected $registry;

    /**
     * @var \Ident\Test\Stubs\Payment
     */
    protected $payment;

    /**
     * @var \Ident\Test\Stubs\Order
     */
    protected $order;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->registry = new InMemoryRegistry();

        $this->order = new Order();
        $this->payment = new Payment();

        $this->getProcessor()->processIdentities($this->order);
        $this->getProcessor()->processIdentities($this->payment);
    }

    /**
     * @test
     */
    public function shouldAttachAndFindIdentities()
    {
        $this->registry->add($this->order);

        $this->assertSame($this->order, $this->registry->get($this->order->getIdentifier()));
        $this->assertTrue($this->registry->contains($this->order));
        $this->assertTrue($this->registry->has($this->order->getIdentifier()));
        $this->assertFalse($this->registry->has($this->payment->getIdentifier()));
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\IdentityAlreadyRegistered
     */
    public function shouldThrowExceptionOnDuplicateIdentities()
    {
        $this->registry->add($this->order);
        $this->registry->add($this->payment);
        $this->registry->add($this->order);
    }

    /**
     * @test
     */
    public function shouldNotThrowExceptionOnDuplicateIdentitiesNotStrict()
    {
        $this->registry = new InMemoryRegistry(false);

        $this->registry->add($this->order);
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $this->assertCount(2, $this->registry->asArray()->all());
    }

    /**
     * @test
     */
    public function shouldRemoveIdentities()
    {
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $this->assertTrue($this->registry->contains($this->payment));
        $this->assertTrue($this->registry->contains($this->order));

        $this->registry->remove($this->payment);

        $this->assertFalse($this->registry->contains($this->payment));
        $this->assertTrue($this->registry->contains($this->order));

        $this->registry->del($this->order->getIdentifier());

        $this->assertFalse($this->registry->contains($this->order));
    }

    /**
     * @test
     */
    public function shouldClear()
    {
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $this->registry->clear();

        $this->assertFalse($this->registry->contains($this->order));
        $this->assertFalse($this->registry->contains($this->payment));
    }

    /**
     * @test
     */
    public function shouldMapCallable()
    {
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $this->registry->map(
            function ($identity) {
                if ($identity instanceof Order) {
                    $identity->addValue(25);
                }

                if ($identity instanceof Payment) {
                    $identity->process();
                }
            }
        );

        $this->assertEquals(25, $this->order->value());
        $this->assertTrue($this->payment->isProcessed());
    }

    /**
     * @test
     */
    public function shouldGetAllItemsAsIterator()
    {
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $all = $this->registry->all();

        $this->assertInstanceOf('\Iterator', $all);
        $this->assertCount(2, $all);

        // check that resetting array mode works
        $all = $this->registry->asArray()->asIterator()->all();

        $this->assertInstanceOf('\Iterator', $all);
        $this->assertCount(2, $all);
    }

    /**
     * @test
     */
    public function shouldGetAllItemsAsArray()
    {
        $this->registry->add($this->payment);
        $this->registry->add($this->order);

        $all = $this->registry->asArray()->all();

        $this->assertTrue(is_array($all));
        $this->assertNotInstanceOf('\Iterator', $all);
        $this->assertSame($this->payment, $all[0]);
        $this->assertSame($this->order, $all[1]);
    }

    /**
     * @test
     *
     * @expectedException \Ident\Exception\IdentityNotFound
     */
    public function shouldThrowExceptionWhenIdentityIsNotFound()
    {
        $this->registry->get($this->order->getIdentifier());
    }
}
