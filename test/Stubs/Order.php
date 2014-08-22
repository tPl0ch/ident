<?php

namespace Ident\Test\Stubs;

use Ident\HasIdentity;

/**
 * Class Order
 */
class Order implements HasIdentity
{
    /**
     * @var OrderId
     */
    private $id;

    /**
     * @var int
     */
    private $value;

    /**
     * @param OrderId $orderId
     */
    public function __construct(OrderId $orderId)
    {
        $this->id = $orderId;
        $this->value = 0;
    }
    /**
     * @return \Ident\IdentifiesObjects
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function equals(HasIdentity $identity)
    {
        return $this->id->equals($identity->getIdentifier());
    }

    /**
     * @param $value
     */
    public function addValue($value)
    {
        $this->value += (int) $value;
    }

    /**
     * @return int
     */
    public function value()
    {
        return $this->value;
    }
}
