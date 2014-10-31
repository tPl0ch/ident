<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;
use Rhumsaa\Uuid\Uuid;

/**
 * Class StringUuidIdentifier
 */
class UuidIdentifier extends AbstractUuidIdentifier
{
    /**
     * @param Uuid $uuid
     *
     * @return void
     */
    protected function extractSignature(Uuid $uuid)
    {
        $this->signature = (string) $uuid;
    }

    /**
     * @return mixed
     */
    public function getIdValue()
    {
        return $this->signature;
    }
}
