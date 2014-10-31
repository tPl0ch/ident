<?php

namespace Ident;

/**
 * Interface MapsClassToIdentity
 */
interface MapsClassToIdentity
{
    /**
     * @param string $className
     *
     * @return mixed
     */
    public function map($className);

    /**
     * @param string $identityClass
     * @param string $className
     *
     * @return void
     */
    public function register($identityClass, $className);

    /**
     * @param string $className
     *
     * @return void
     */
    public function remove($className);
}
