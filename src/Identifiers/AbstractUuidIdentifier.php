<?php

namespace Ident\Identifiers;

use Ident\HasScalarId;
use Ident\IdentifiesObjects;
use Ident\Traits\Identifier;
use Ident\Exception\IdentExceptions;
use Rhumsaa\Uuid\Uuid;

/**
 * Class AbstractUuidIdentifier
 */
abstract class AbstractUuidIdentifier
    implements IdentifiesObjects, HasScalarId
{
    const UUID_CONVERTER = '\Rhumsaa\Uuid\Uuid::fromString';

    use Identifier;

    /**
     * @param string|Uuid $uuid
     *
     * @return BinaryUuidIdentifier|IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public static function fromSignature($uuid)
    {
        $converter = static::UUID_CONVERTER;

        if (!$uuid instanceof Uuid) {
            try {
                $uuid = call_user_func($converter, (string) $uuid);
            } catch (\Exception $e) {
                throw IdentExceptions::invalidSignature((string) $uuid);
            }
        }

        return new static($uuid);
    }

    /**
     * @param Uuid $uuid
     *
     * @return void
     */
    abstract protected function extractSignature(Uuid $uuid);

    /**
     * @param Uuid $uuid
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public function __construct(Uuid $uuid)
    {
        $this->extractSignature($uuid);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->signature;
    }
}
