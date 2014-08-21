<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;

use Rhumsaa\Uuid\Uuid;

/**
 * Class BinaryUuidIdentifier
 */
class BinaryUuidIdentifier implements IdentifiesObjects
{
    /**
     * @var string
     */
    private $uuidBinaryString;

    /**
     * @param string|Uuid $uuid
     *
     * @return BinaryUuidIdentifier|IdentifiesObjects
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public static function fromSignature($uuid)
    {
        if (!$uuid instanceof Uuid) {
            try {
                $uuid = Uuid::fromBytes((string) $uuid);
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
        $this->uuidBinaryString = $uuid->getBytes();
    }

    /**
     * @return string
     */
    public function signature()
    {
        return $this->uuidBinaryString;
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
    final public function toHex()
    {
        return bin2hex($this->uuidBinaryString);
    }
}
