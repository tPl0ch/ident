<?php

namespace Ident\Test\Stubs;

use Doctrine\ORM\Mapping as ORM;
use Ident\Doctrine\Mapping\Annotation as Ident;
use Ident\HasIdentity;
use Ident\IdentifiesObjects;

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
     * @Ident\IdType(idClass="Ident\Test\Stubs\OrderId")
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
     * @param IdentifiesObjects $orderId
     */
    public function __construct(IdentifiesObjects $orderId)
    {
        $this->id = $orderId;
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

    /**
     * @param Payment $payment
     */
    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;
    }
}
