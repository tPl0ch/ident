<?php

namespace Ident\Test;

use Ident\Doctrine\Subscriber\IdentitySubscriber;
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
        $this->order->addValue(25);

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
}
