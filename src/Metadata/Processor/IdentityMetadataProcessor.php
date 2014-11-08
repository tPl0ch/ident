<?php

namespace Ident\Metadata\Processor;

use Ident\IdentifiesObjects;
use Ident\MapsClassToIdentity;
use Ident\Metadata\PropertyMetadata;
use Ident\ServiceLocatorInterface;
use Metadata\MetadataFactoryInterface;

/**
 * Class IdentityMetadataProcessor
 */
class IdentityMetadataProcessor
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
     * @var MapsClassToIdentity
     */
    protected $mapper;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param ServiceLocatorInterface  $serviceLocator
     * @param MapsClassToIdentity      $mapper
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        ServiceLocatorInterface $serviceLocator,
        MapsClassToIdentity $mapper
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
    public function processIdentities($object, $convertIdentifiers = false)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }

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
        $type = $this->getType($propertyMetadata);
        $factory = $propertyMetadata->factory;

        list($callable, $parameters) = $this->getCallable($factory);

        if (!is_callable($callable)) {
            throw new \Exception("Callable not callable");
        }

        $identifier = $type::fromSignature(call_user_func_array($callable, $parameters));
        $propertyMetadata->setValue($object, $identifier);
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

        if (is_string($factory) || (is_array($factory) && !isset($factory['service']))) {
            $callable = $factory;
        }

        if (is_array($factory) && isset($factory['service'])) {
            $callable = [
                $this->serviceLocator->get($factory['service']),
                $factory['method']
            ];

            $parameters = $factory['params'];
        }

        return [$callable, $parameters];
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

        if (!$refClass->isSubclassOf($identifierClass) && !$refClass->isInstance($identifier)) {
            throw new \Exception(
                sprintf(
                    "Identifier class '%s' must be an instance or subclass of '%s'",
                    $refClass->name,
                    $identifierClass
                )
            );
        }

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

        try {
            $type = $this->mapper->map($type);
        } catch (\Exception $e) {
            // If the mapper has no alias, $type should be treated as FQCN
        }

        return $type;
    }
}
