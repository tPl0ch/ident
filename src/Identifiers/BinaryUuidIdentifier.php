<?php

namespace Ident\Identifiers;

use Rhumsaa\Uuid\Uuid;

/**
 * Class BinaryUuidIdentifier
 */
class BinaryUuidIdentifier extends AbstractUuidIdentifier
{
    const UUID_CONVERTER = '\Rhumsaa\Uuid\Uuid::fromBytes';

    /**
     * @var string
     */
    protected $idValue;

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
     * @return string
     */
    public function getIdValue()
    {
        return $this->idValue;
    }
}
