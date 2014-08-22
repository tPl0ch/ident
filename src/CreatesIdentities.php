<?php

namespace Ident;

/**
 * Interface CreatesIdentities
 */
interface CreatesIdentities
{
    /**
     * @param mixed|null $context
     *
     * @return \Ident\IdentifiesObjects
     */
    public function identify($context = null);
}
