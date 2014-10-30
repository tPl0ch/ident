<?php

namespace Ident\Identifiers;

use Ident\Factory\UuidIdentifierFactory;
use Ident\IdentifiesObjects;
use Ident\Traits\Identifier;
use Rhumsaa\Uuid\Uuid;

/**
 * Class AbstractUuidIdentifier
 */
abstract class AbstractUuidIdentifier implements IdentifiesObjects
{
    use Identifier;

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
}
