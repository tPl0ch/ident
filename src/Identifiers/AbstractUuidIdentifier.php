<?php

namespace Ident\Identifiers;

use Ident\IdentifiesObjects;

/**
 * Class AbstractUuidIdentifier
 */
abstract class AbstractUuidIdentifier implements IdentifiesObjects
{
    /**
     * @var mixed
     */
    protected $signature;

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    final public function equals(IdentifiesObjects $id)
    {
        return $this->signature() === $id->signature();
    }

    /**
     * @return mixed
     */
    public function signature()
    {
        return $this->signature;
    }
}
