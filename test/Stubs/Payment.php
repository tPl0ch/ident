<?php

namespace Ident\Test\Stubs;

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
    private $processed;

    /**
     * @param PaymentId $id
     */
    public function __construct(PaymentId $id)
    {
        $this->id = $id;
        $this->processed = false;
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
