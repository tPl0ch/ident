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
            throw IdentExceptions::classNotMappable();
        }

        return $this->identityClasses[$this->classes[$className]];
    }

    /**
     * @param string|array $classNames
     * @param string       $identityClass
     *
     * @return void
     *
     * @throws \Ident\Exception\ClassAlreadyMappedException
     */
    public function register($classNames, $identityClass)
    {
        if (is_array($classNames)) {
            foreach ($classNames as $class) {
                $this->register($class, $identityClass);
            }

            return;
        }

        $className = (string) $classNames;

        if (isset($this->classes[$className])) {
            throw IdentExceptions::classAlreadyMapped();
        }

        $identityClass = (string) $identityClass;

        $this->validateClass($identityClass, '\Ident\IdentifiesObjects');
        $this->validateClass($className, '\Ident\HasIdentity');


        if ($key = array_search($identityClass, $this->identityClasses, true)) {
            $this->classes[$className] = $key;
            $this->classIdentityMap[$identityClass]++;

            return;
        }

        end($this->identityClasses);
        $key = key($this->identityClasses) + 1;

        $this->identityClasses[$key] = $identityClass;
        $this->classes[$className] = $key;
        $this->classIdentityMap[$identityClass] = 1;
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
     *
     * @throws \Ident\Exception\ClassNotFoundException
     * @throws \Ident\Exception\TypeNotAllowed
     */
    protected function validateClass($class, $interface)
    {
        try {
            $refClass = new \ReflectionClass($class);
        } catch (\ReflectionException $e) {
            throw IdentExceptions::classNotFound();
        }

        if (!$refClass->implementsInterface($interface)) {
            throw IdentExceptions::typeNotAllowed();
        }
    }
}
