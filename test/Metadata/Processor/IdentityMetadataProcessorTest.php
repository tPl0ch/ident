<?php

namespace Ident\Test\Metadata\Processor;

use Ident\Test\AbstractIdentTest;
use Ident\Test\Stubs\Order;

/**
 * Class IdentityMetadataProcessorTest
 */
class IdentityMetadataProcessorTest extends AbstractIdentTest
{
    /**
     * @test
     */
    public function shouldProcessAnnotations()
    {
        $order = new Order();
        $this->getProcessor()->identify($order);

        $this->assertInstanceOf(
            'Ident\Test\Stubs\OrderId',
            $order->getIdentifier()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $order->getApplicationId()
        );

        $this->assertInstanceOf(
            'Ident\Identifiers\StringIdentifier',
            $order->getCorrelationId()
        );
    }

    /**
     * @test
     */
    public function shouldProcessAnnotationsOnlyOnce()
    {
        $order = new Order();
        $this->getProcessor()->identify($order);

        $id = $order->getIdentifier();
        $correlationId = $order->getCorrelationId();
        $appId = $order->getApplicationId();

        $this->getProcessor()->identify($order);

        $this->assertTrue($id->equals($order->getIdentifier()));
        $this->assertTrue($correlationId->equals($order->getCorrelationId()));
        $this->assertTrue($appId->equals($order->getApplicationId()));
    }
}
