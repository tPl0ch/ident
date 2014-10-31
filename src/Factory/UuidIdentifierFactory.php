<?php

namespace Ident\Factory;

use Ident\CreatesIdentities;
use Ident\MapsClassToIdentity;
use Rhumsaa\Uuid\Uuid;

/**
 * Class UuidIdentifierFactory
 */
class UuidIdentifierFactory implements CreatesIdentities
{
    /**
     * @var MapsClassToIdentity
     */
    protected $identityMapper;

    /**
     * @param MapsClassToIdentity $identityMapper
     */
    public function __construct(MapsClassToIdentity $identityMapper)
    {
        $this->identityMapper = $identityMapper;
    }

    /**
     * @param mixed|null $context
     *
     * @return \Ident\IdentifiesObjects
     */
    public function identify($context)
    {
        if (is_object($context)) {
            $class = get_class($context);
        } else {
            $class = (string) $context;
        }

        $identityClass = $this->identityMapper->map($class);

        return new $identityClass(Uuid::uuid4());
    }
}
