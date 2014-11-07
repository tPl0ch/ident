<?php

namespace Ident\Test\Doctrine;

use Doctrine\ORM\Tools\SchemaTool;
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

        /** @var \Ident\Test\Stubs\Order $persistedOrder */
        $persistedOrder = $this->manager->find(
            get_class($this->order),
            $this->order->getIdentifier()
        );

        $this->assertInstanceOf(
            'Ident\Test\Stubs\OrderId',
            $persistedOrder->getIdentifier()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $persistedOrder->getApplicationId()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $persistedOrder->getCorrelationId()
        );
    }
}
