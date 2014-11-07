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
            if (isset($propertyMetadata->type) && !$propertyMetadata->getValue($object) instanceof IdentifiesObjects) {
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
        $type    = $propertyMetadata->type;
        $factory = $propertyMetadata->factory;

        try {
            $type = $this->mapper->map($type);
        } catch (\Exception $e) {
            // If the mapper has no alias, $type should be treated as FQCN
        }

        list($callable, $parameters) = $this->getCallable($factory);

        if (!is_callable($callable)) {
            throw new \Exception("Callable not callable");
        }

        $identifier = $type::fromSignature(call_user_func_array($callable, $parameters));
        $propertyMetadata->setValue($object, $identifier);
    }

    /**
     * @param string|array $factory
     *
     * @return array
     */
    private function getCallable($factory)
    {
        $callable   = null;
        $parameters = [];

        if (null === $factory) {
            return [$callable, $parameters];
        }

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

        return [$callable, $parameters];
    }
}
