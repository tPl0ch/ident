<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;

/**
 * Class NullIdentifier
 */
final class NullIdentifier implements IdentifiesObjects
{
    /**
     * @param mixed $signature
     *
     * @return IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    public static function fromSignature($signature)
    {
        if ($signature !== null && $signature !== '') {
            throw IdentExceptions::invalidSignature('NOT NULL');
        }

        return new self();
    }

    /**
     * This method returns a unique identity representation
     *
     * @return mixed
     */
    public function signature()
    {
        return null;
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id)
    {
        return $id instanceof self;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return '';
    }
}
