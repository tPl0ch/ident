<?php

namespace Ident\Test\Stubs;

use Ident\CreatesIdentities;
use Ident\HasIdentity;

/**
 * Class Order
 *
 * @ORM\Entity()
 */
class Order implements HasIdentity
{
    /**
     * @var OrderId
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid_binary")
     */
    private $identifier;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={default="0"})
     */
    private $value;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @param CreatesIdentities $idFactory
     */
    public function __construct(CreatesIdentities $idFactory)
    {
        $this->identifier = $idFactory->identify($this);
        $this->value = 0;
    }
    /**
     * @return \Ident\IdentifiesObjects
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function equals(HasIdentity $identity)
    {
        return $this->identifier->equals($identity->getIdentifier());
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

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }
}
