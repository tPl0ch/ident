<?php

namespace Ident\Metadata\Driver;

use Doctrine\Common\Annotations\Reader;
use Ident\Metadata\PropertyMetadata;
use Metadata\ClassMetadata;
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;

/**
 * Class AnnotationDriver
 */
class AnnotationDriver implements DriverInterface
{
    /** @var string */
    const ANNOTATION_ID_TYPE = 'Ident\\Metadata\\Annotation\\IdType';

    /**
     * @var Reader
     */
    protected $reader;

    /**
     * @param Reader              $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param \ReflectionClass $class
     *
     * @return \Metadata\ClassMetadata
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $className = $class->name;
        $classMetadata = new MergeableClassMetadata($className);

        foreach ($class->getProperties() as $reflectionProperty) {
            $this->processPropertyMetadata($className, $reflectionProperty, $classMetadata);
        }

        return $classMetadata;
    }

    /**
     * @param string              $className
     * @param \ReflectionProperty $reflectionProperty
     * @param ClassMetadata       $classMetadata
     */
    protected function processPropertyMetadata(
        $className,
        \ReflectionProperty $reflectionProperty,
        ClassMetadata $classMetadata
    ) {
        $propertyMetadata = new PropertyMetadata($className, $reflectionProperty->getName());

        /** @var \Ident\Metadata\Annotation\IdType|null $annotation */
        $annotation = $this->reader->getPropertyAnnotation(
            $reflectionProperty,
            self::ANNOTATION_ID_TYPE
        );

        if (null !== $annotation) {
            $propertyMetadata->factory = $annotation->factory;
            $propertyMetadata->type    = $annotation->type;
        }

        $classMetadata->addPropertyMetadata($propertyMetadata);
    }
}
