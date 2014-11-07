<?php

namespace Ident\Test\Stubs;

use Ident\HasIdentity;
use Ident\Metadata\Annotation as Ident;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Order
 *
 * @ORM\Entity()
 * @ORM\Table("orders")
 */
class Order implements HasIdentity
{
    /**
     * @var OrderId
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid_binary")
     * @Ident\IdType(
     *  type="Ident\Test\Stubs\OrderId",
     *  factory="\Rhumsaa\Uuid\Uuid::uuid4"
     * )
     */
    private $identifier;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"="0"})
     */
    private $value = 0;

    /**
     * @var Payment
     */
    private $payment;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=40)
     * @Ident\IdType(
     *  type="Ident\Identifiers\StringIdentifier",
     *  factory={"service"="hash.factory", "method"="hash", "params"={"sha1"}}
     * )
     */
    private $applicationId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=64)
     * @Ident\IdType(
     *  type="string",
     *  factory={"service"="hash.factory", "method"="hash", "params"={"sha256"}}
     * )
     */
    private $correlationId;

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

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }
}
