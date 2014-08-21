<?php

namespace Ident\Identifiers;

use Ident\Exception\IdentExceptions;
use Ident\IdentifiesObjects;

/**
 * Class ClassIdentifier
 */
class ClassIdentifier implements IdentifiesObjects
{
    /**
     * @var string
     */
    protected static $delimiter = '@@';

    /**
     * @var string
     */
    protected $className;

    /**
     * @var string
     */
    protected $id;

    /**
     * @param string $signature
     *
     * @return IdentifiesObjects|ClassIdentifier
     *
     * @throws \Ident\Exception\InvalidSignature
     */
    final public static function fromSignature($signature)
    {
        list($className, $id) = explode(static::$delimiter, $signature);

        if (!$className || !$id) {
            throw IdentExceptions::invalidSignature();
        }

        return new static($className, $id);
    }

    /**
     * @param string $className
     * @param string $id
     */
    final public function __construct($className, $id)
    {
        $this->className = $className;
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function signature()
    {
        return sprintf('%s%s%s', $this->className, static::$delimiter, $this->id);
    }

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function equals(IdentifiesObjects $id)
    {
        return $this->signature() === $id->signature();
    }
}
