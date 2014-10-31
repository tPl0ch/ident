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

    /**
     * @var string
     */
    protected $class;

    /**
     * @var string
     */
    protected $baseClass;

    /**
     * @param string|null $class
     * @param string|null $baseClass
     */
    public function __construct($class = null, $baseClass = null)
    {
        if (!$class) {
            $class = static::DEFAULT_CLASS;
        }

        $this->validateClass($class);
        if ($baseClass) {
            $this->validateClass($baseClass);
        }

        $this->class = $class;
        $this->baseClass = $baseClass;
    }

    /**
     * @return \Ident\IdentifiesObjects
     */
    public function identify()
    {
        $class = $this->class;

        if ($this->baseClass) {
            $refClass = new \ReflectionClass($class);
            if (!$refClass->isSubclassOf($this->baseClass)) {
                $baseClass = $this->baseClass;

                throw new \InvalidArgumentException("Class '{$this->class}' must be a subclass of '$baseClass'");
            }
            unset($refClass);
        }

        return new $class(Uuid::uuid4());
    }

    /**
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->validateClass($class);

        $this->class = (string) $class;

        return $this;
    }

    /**
     * @param string $class
     */
    protected function validateClass($class)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException("Class '$class' not found or could not be autoloaded");
        }
    }
}
