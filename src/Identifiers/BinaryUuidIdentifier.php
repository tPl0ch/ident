<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;
use Rhumsaa\Uuid\Uuid;

/**
 * Class BinaryUuidIdentifier
 */
class BinaryUuidIdentifier extends AbstractUuidIdentifier
{
    /**
     * @var string
     */
    protected $idValue;

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
     * @return string
     */
    final public function toHex()
    {
        return bin2hex($this->signature);
    }

    /**
     * @param Uuid $uuid
     *
     * @return void
     */
    protected function extractSignature(Uuid $uuid)
    {
        $this->signature = $uuid->getBytes();
        $this->idValue   = $uuid->toString();
    }

    /**
     * @return mixed
     */
    public function getIdValue()
    {
        return $this->idValue;
    }
}
