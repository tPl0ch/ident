<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;

/**
 * Class StringIdentifier
 */
class StringIdentifier implements IdentifiesObjects
{
    /**
     * @var string
     */
    protected $identifier;

    /**
     * @param string $id
     *
     * @return static
     */
    public static function create($id)
    {
        return new static($id);
    }

    /**
     * @param string $identifier
     */
    final public function __construct($identifier)
    {
        $this->identifier = (string) $identifier;
    }

    /**
     * @param mixed $signature
     *
     * @return IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    public static function fromSignature($signature)
    {
        $signature = (string) $signature;

        if (!is_string($signature)) {
            throw IdentExceptions::invalidSignature($signature);
        }

        return new static($signature);
    }

    /**
     * This method returns a unique identity representation
     *
     * @return string
     */
    public function signature()
    {
        return $this->identifier;
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id)
    {
        return $this->signature() === $id->signature();
    }
}
