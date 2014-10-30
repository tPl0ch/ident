<?php

namespace Ident\Factory;

use Ident\CreatesIdentities;
use Rhumsaa\Uuid\Uuid;

/**
 * Class UuidIdentifierFactory
 */
class UuidIdentifierFactory implements CreatesIdentities
{
    /** @var string */
    const DEFAULT_CLASS = '\Ident\Identifiers\StringUuidIdentifier';
    const BASE_CLASS = '\Ident\Identifiers\AbstractUuidIdentifier';

    /**
     * @param string|null $class
     *
     * @return \Ident\IdentifiesObjects
     */
    public function identify($class = null)
    {
        if (!$class) {
            $class = static::DEFAULT_CLASS;
        }

        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class '$class' not found or could not be autoloaded");
        }

        $refClass = new \ReflectionClass($class);
        if (!$refClass->isSubclassOf(static::BASE_CLASS)) {
            $baseClass = static::BASE_CLASS;

            throw new \InvalidArgumentException("Class '$class' must be a subclass of '$baseClass'");
        }
        unset($refClass);

        return new $class(Uuid::uuid4());
    }
}
