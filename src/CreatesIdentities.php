<?php

namespace Ident;

/**
 * Interface CreatesIdentities
 */
interface CreatesIdentities
{
    /**
     * @param mixed $context
     *
     * @return \Ident\IdentifiesObjects
     *
     * @throws \Ident\Exception\ClassNotMappableException
     */
    public function identify($context);
}
