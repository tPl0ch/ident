<?php

namespace Ident\Test\Stubs;

use Ident\HasIdentity;
use Ident\Metadata\Annotation as Ident;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Payment
 *
 * @ORM\Entity()
 * @ORM\Table("payments")
 */
class Payment implements HasIdentity
{
    /**
     * @var PaymentId
     *
     * @ORM\Id()
     * @ORM\Column(type="uuid_string")
     * @Ident\IdType(
     *  type="Ident\Test\Stubs\PaymentId",
     *  factory="\Rhumsaa\Uuid\Uuid::uuid4"
     * )
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $processed = false;

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
