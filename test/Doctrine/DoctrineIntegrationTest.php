<?php

namespace Ident\Test\Doctrine;

use Ident\Doctrine\Subscriber\IdentitySubscriber;
use Ident\Test\AbstractIdentTest;
use Ident\Test\Stubs\Order;

/**
 * Class DoctrineIntegrationTest
 */
class DoctrineIntegrationTest extends AbstractIdentTest
{
    /**
     * @var \Ident\Test\Stubs\Order
     */
    protected $order;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->order = new Order();
        $this->manager = $this->getContainer()->get('em');

        $this
            ->manager
            ->getEventManager()
            ->addEventSubscriber(
                new IdentitySubscriber($this->getProcessor())
            );
    }

    /**
     * @test
     */
    public function shouldAddIdsOnPrePersist()
    {
        $this->manager->persist($this->order);

        $this->assertInstanceOf(
            'Ident\Test\Stubs\OrderId',
            $this->order->getIdentifier()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $this->order->getApplicationId()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $this->order->getCorrelationId()
        );
    }

    /**
     * @test
     */
    public function shouldAddIdsOnPostLoad()
    {
        $this->manager->persist($this->order);
        $this->manager->flush();

        $identifier = $this->order->getIdentifier();
        $correlationId = $this->order->getCorrelationId();
        $applicationId = $this->order->getApplicationId();

        $this->manager->detach($this->order);
        unset($this->order);

        /** @var \Ident\Test\Stubs\Order $persistedOrder */
        $persistedOrder = $this->manager->find(
            'Ident\Test\Stubs\Order',
            $identifier
        );

        $this->assertInstanceOf(
            'Ident\Test\Stubs\OrderId',
            $persistedOrder->getIdentifier()
        );

        $this->assertTrue(
            $identifier->equals($persistedOrder->getIdentifier())
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $persistedOrder->getApplicationId()
        );

        $this->assertTrue(
            $applicationId->equals($persistedOrder->getApplicationId())
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $persistedOrder->getCorrelationId()
        );

        $this->assertTrue(
            $correlationId->equals($persistedOrder->getCorrelationId())
        );
    }

    protected function tearDown()
    {
        if (isset($this->order)) {
            $this->manager->detach($this->order);
            unset($this->order);
        }
    }
}
