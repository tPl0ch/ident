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
}
