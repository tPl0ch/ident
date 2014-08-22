<?php

namespace Ident\Identifiers;

use Ident\Factory\UuidIdentifierFactory;
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
     * @var UuidIdentifierFactory
     */
    private static $factory;

    /**
     * @return UuidIdentifierFactory
     */
    private static function factory()
    {
        if (!self::$factory) {
            self::$factory = new UuidIdentifierFactory();
        }

        return self::$factory;
    }

    /**
     * @return IdentifiesObjects
     */
    final public static function create()
    {
        return self::factory()->identify(static::class);
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
