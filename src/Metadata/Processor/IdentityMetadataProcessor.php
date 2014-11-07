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

    public function processIdentities($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('No object provided');
        }

        $classMetadata = $this->metadataFactory->getMetadataForClass(get_class($object));

        /** @var \Ident\Metadata\PropertyMetadata $propertyMetadata */
        foreach ($classMetadata->propertyMetadata as $propertyMetadata) {
            if (isset($propertyMetadata->type)) {
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

        $type    = $propertyMetadata->type;
        $factory = $propertyMetadata->factory;

        try {
            $type = $this->mapper->map($type);
        } catch (\Exception $e) {
        }

        $refClass = new \ReflectionClass($type);
        if (!$refClass->implementsInterface('Ident\IdentifiesObjects')) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Type '%s' must implement 'Ident\\IdentifiesObjects'",
                    $type
                )
            );
        }

        $callable   = null;
        $parameters = [];

        if (is_string($factory) || is_array($factory) && !isset($factory['service'])) {
            $callable = $factory;
        }

        if (is_array($factory) && isset($factory['service'])) {
            $callable = [
                $this->serviceLocator->get($factory['service']),
                $factory['method']
            ];

            $parameters = $factory['params'];
        }

        if (!is_callable($callable)) {
            throw new \Exception("Callable not callable");
        }

        $identifier = $type::fromSignature(call_user_func_array($callable, $parameters));
        $propertyMetadata->setValue($object, $identifier);
    }
}
