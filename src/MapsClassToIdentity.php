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
     * @param string $className
     * @param string $identityClass
     *
     * @return void
     */
    public function register($className, $identityClass);

    /**
     * @param array  $classNames
     * @param string $identityClass
     *
     * @return void
     */
    public function registerMany(array $classNames, $identityClass);

    /**
     * @param string $className
     *
     * @return void
     */
    public function remove($className);
}
