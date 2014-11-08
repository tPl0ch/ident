<?php

namespace Ident\Metadata\Processor;

use Ident\CreatesIdentities;
use Ident\IdentifiesObjects;
use Ident\MapsAliasToIdentity;
use Ident\Metadata\PropertyMetadata;
use Ident\ServiceLocatorInterface;
use Metadata\MetadataFactoryInterface;

/**
 * Class IdentityMetadataProcessor
 */
class IdentityMetadataProcessor implements CreatesIdentities
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var MapsAliasToIdentity
     */
    protected $mapper;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param ServiceLocatorInterface  $serviceLocator
     * @param MapsAliasToIdentity|null $mapper
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        ServiceLocatorInterface $serviceLocator,
        MapsAliasToIdentity $mapper = null
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->serviceLocator = $serviceLocator;
        $this->mapper = $mapper;
    }

    /**
     * @param object $object
     * @param bool   $convertIdentifiers
     *
     * @return object
     * @throws \Exception
     */
    public function identify($object, $convertIdentifiers = false)
    {
        $this->validateObject($object);

        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));

        /** @var \Ident\Metadata\PropertyMetadata $propertyMetadata */
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->type)) {
                if ($convertIdentifiers) {
                    $this->convertIdentifiers($object, $propertyMetadata);

                    continue;
                }

                $this->processMetadata($object, $propertyMetadata);
            }
        }

        return $object;
    }

    /**
     * @param object           $object
     * @param PropertyMetadata $propertyMetadata
     *
     * @throws \Exception
     */
    protected function processMetadata($object, PropertyMetadata $propertyMetadata)
    {
        if ($propertyMetadata->getValue($object) instanceof IdentifiesObjects) {
            return;
        }

        $type = $this->getType($propertyMetadata);
        $factory = $propertyMetadata->factory;

        list($callable, $parameters) = $this->getCallable($factory);

        $this->verifyCallable($callable);

        $identifier = $type::fromSignature(call_user_func_array($callable, $parameters));
        $propertyMetadata->setValue($object, $identifier);
    }

    /**
     * @param                  $object
     * @param PropertyMetadata $propertyMetadata
     *
     * @return IdentifiesObjects
     * @throws \Exception
     */
    protected function convertIdentifiers($object, PropertyMetadata $propertyMetadata)
    {
        /** @var \Ident\IdentifiesObjects $identifier */
        $type            = $this->getType($propertyMetadata);
        $identifier      = $propertyMetadata->getValue($object);

        if (!$identifier instanceof IdentifiesObjects) {
            $propertyMetadata->setValue($object, $type::fromSignature($identifier));

            return;
        }

        $refClass        = new \ReflectionClass($type);
        $identifierClass = get_class($identifier);

        $this->confirmIdentifierClasses($refClass, $identifierClass, $identifier);
        $propertyMetadata->setValue($object, $type::fromSignature($identifier->signature()));
    }

    /**
     * @param PropertyMetadata $propertyMetadata
     *
     * @return mixed|string
     */
    private function getType(PropertyMetadata $propertyMetadata)
    {
        $type = $propertyMetadata->type;

        if ($this->mapper) {
            try {
                $type = $this->mapper->map($type);
            } catch (\Exception $e) {
                // If the mapper has no alias, $type should be treated as FQCN
            }
        }

        return $type;
    }

    /**
     * @param string|array|null $factory
     *
     * @return array
     */
    private function getCallable($factory = null)
    {
        $callable   = null;
        $parameters = [];

        if ($this->isScalarCallable($factory)) {
            $callable = $factory;
        }

        if ($this->isArrayCallable($factory)) {
            $callable = [
                $this->serviceLocator->get($factory['service']),
                $factory['method']
            ];

            $parameters = $factory['params'];
        }

        return [$callable, $parameters];
    }

    /**
     * @param $refClass
     * @param $identifierClass
     * @param $identifier
     *
     * @throws \Exception
     */
    private function confirmIdentifierClasses(
        \ReflectionClass $refClass,
        $identifierClass,
        IdentifiesObjects $identifier
    ) {
        if (!$refClass->isSubclassOf($identifierClass) && !$refClass->isInstance($identifier)) {
            throw new \Exception(
                sprintf(
                    "Identifier class '%s' must be an instance or subclass of '%s'",
                    $refClass->name,
                    $identifierClass
                )
            );
        }
    }

    /**
     * @param $object
     */
    private function validateObject($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }
    }

    /**
     * @param $factory
     *
     * @return bool
     */
    private function isScalarCallable($factory)
    {
        return is_string($factory) || (is_array($factory) && !isset($factory['service']));
    }

    /**
     * @param $factory
     *
     * @return bool
     */
    private function isArrayCallable($factory)
    {
        return is_array($factory) && isset($factory['service']);
    }

    /**
     * @param $callable
     *
     * @throws \Exception
     */
    protected function verifyCallable($callable)
    {
        if (!is_callable($callable)) {
            throw new \Exception("Callable not callable");
        }
    }
}
