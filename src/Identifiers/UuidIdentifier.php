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
     * @param string|Uuid $uuid
     *
     * @return UuidIdentifier|IdentifiesObjects
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
