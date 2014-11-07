<?php

namespace Ident\Factory;

use Ident\Exception\IdentExceptions;
use Ident\MapsClassToIdentity;

/**
 * Class InMemoryClassToIdentityMapper
 */
class InMemoryClassToIdentityMapper implements MapsClassToIdentity
{
    /**
     * @var array
     */
    protected $classIdentityMap;

    /**
     * @var array
     */
    protected $classes;

    /**
     * @var array
     */
    protected $identityClasses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->classes          = [];
        $this->identityClasses  = [];
        $this->classIdentityMap = [];
    }

    /**
     * @param string $className
     *
     * @return mixed
     *
     * @throws \Ident\Exception\ClassNotMappableException
     */
    public function map($className)
    {
        if (!isset($this->classes[$className])) {
            throw IdentExceptions::classNotMappable($className);
        }

        return $this->identityClasses[$this->classes[$className]];
    }

    /**
     * @param string $className
     * @param string $identityClass
     *
     * @return void
     *
     * @throws \Ident\Exception\ClassAlreadyMappedException
     */
    public function register($className, $identityClass)
    {
        $className = (string) $className;

        if (isset($this->classes[$className])) {
            throw IdentExceptions::classAlreadyMapped($className);
        }

        $identityClass = (string) $identityClass;

        $this->validateClass($identityClass, '\Ident\IdentifiesObjects');
        $this->validateClass($className, '\Ident\HasIdentity', false);

        if ($key = array_search($identityClass, $this->identityClasses, true)) {
            $this->classes[$className] = $key;
            $this->classIdentityMap[$identityClass]++;

            return;
        }

        $this->addMapAndIncrement($className, $identityClass);
    }

    /**
     * @param array  $classNames
     * @param string $identityClass
     *
     * @return void
     */
    public function registerMany(array $classNames, $identityClass)
    {
        foreach ($classNames as $class) {
            $this->register($class, $identityClass);
        }
    }

    /**
     * @param string $className
     *
     * @return void
     */
    public function remove($className)
    {
        $className = (string) $className;

        if (!isset($this->classes[$className])) {
            return;
        }

        $key = $this->classes[$className];
        unset($this->classes[$className]);

        if (--$this->classIdentityMap[$this->identityClasses[$key]] === 0) {
            unset($this->classIdentityMap[$this->identityClasses[$key]]);
            unset($this->identityClasses[$key]);
        }
    }

    /**
     * @param string $class
     * @param string $interface
     * @param bool   $mustExist
     *
     * @throws \Ident\Exception\ClassNotFoundException
     * @throws \Ident\Exception\TypeNotAllowed
     */
    protected function validateClass($class, $interface, $mustExist = true)
    {
        try {
            $refClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            if ($mustExist) {
                throw IdentExceptions::classNotFound($class);
            }

            return;
        }

        if (!$refClass->implementsInterface($interface)) {
            throw IdentExceptions::typeNotAllowed($class);
        }
    }

    /**
     * @param string $className
     * @param string $identityClass
     */
    private function addMapAndIncrement($className, $identityClass)
    {
        end($this->identityClasses);
        $key = key($this->identityClasses) + 1;

        $this->identityClasses[$key]            = $identityClass;
        $this->classes[$className]              = $key;
        $this->classIdentityMap[$identityClass] = 1;
    }
}
