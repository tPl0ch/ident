<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;
use Ident\IdentityCanBeCompared;

/**
 * Class StringIdentifier
 */
class StringIdentifier implements IdentifiesObjects, IdentityCanBeCompared
{
    /**
     * @var string
     */
    protected $signature;

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
     * @param string $signature
     */
    final public function __construct($signature)
    {
        $this->signature = (string) $signature;
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
        return $this->signature;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->signature;
    }
}
