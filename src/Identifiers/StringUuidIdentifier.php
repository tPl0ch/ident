<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;

use Rhumsaa\Uuid\Uuid;

/**
 * Class StringUuidIdentifier
 */
class StringUuidIdentifier implements IdentifiesObjects
{
    /**
     * @var string
     */
    private $uuidString;

    /**
     * @param string|Uuid $uuid
     *
     * @return StringUuidIdentifier|IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public static function fromSignature($uuid)
    {
        if (!$uuid instanceof Uuid) {
            try {
                $uuid = Uuid::fromString((string) $uuid);
            } catch (\Exception $e) {
                throw IdentExceptions::invalidSignature();
            }
        }

        return new static($uuid);
    }

    /**
     * @param Uuid $uuid
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public function __construct(Uuid $uuid)
    {
        $this->uuidString = (string) $uuid;
    }

    /**
     * @return string
     */
    public function signature()
    {
        return $this->uuidString;
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
