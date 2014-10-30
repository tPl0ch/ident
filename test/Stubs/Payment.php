<?php

namespace Ident\Test\Stubs;

use Doctrine\ORM\Mapping as ORM;
use Ident\Doctrine\Mapping\Annotation as Ident;
use Ident\HasIdentity;
use Ident\IdentifiesObjects;

/**
 * Class Payment
 *
 * @ORM\Entity()
 */
class Payment implements HasIdentity
{
    /**
     * @var PaymentId
     *
     * @ORM\Column(type="uuid_string")
     * @ORM\Id()
     * @Ident\IdType(idClass="Ident\Test\Stubs\PaymentId")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={default="0"})
     */
    private $processed = false;

    /**
     * @param IdentifiesObjects $id
     */
    public function __construct(IdentifiesObjects $id)
    {
        $this->id = $id;
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
     * @return void
     */
    public function process()
    {
        $this->processed = true;
    }

    /**
     * @return bool
     */
    public function isProcessed()
    {
        return $this->processed;
    }
}
