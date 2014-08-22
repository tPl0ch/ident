<?php

namespace Ident\Identifiers;

use Ident\IdentifiesObjects;
use Rhumsaa\Uuid\Uuid;

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
     * @param object $object
     *
     * @return static
     */
    final public static function create($object = null)
    {
        return new static(Uuid::uuid4());
    }

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
