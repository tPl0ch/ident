<?php

namespace Ident\Test\Stubs;

use Ident\CreatesIdentities;
use Ident\HasIdentity;

/**
 * Class Payment
 */
class Payment implements HasIdentity
{
    /**
     * @var PaymentId
     */
    private $id;

    /**
     * @var bool
     */
    private $processed = false;

    /**
     * @param CreatesIdentities $idFactory
     */
    public function __construct(CreatesIdentities $idFactory)
    {
        $this->id = $idFactory->identify($this);
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
