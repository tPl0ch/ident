<?php

namespace Ident\Metadata\Annotation;

/**
 * Class IdType
 *
 * @Annotation
 * @Target("PROPERTY")
 */
final class IdType
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string|array|null
     */
    public $factory;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->type = $this->validateClassData($data);
        $this->factory = $this->validateFactoryData($data);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    private function validateClassData(array $data)
    {
        $data = $this->verifyType($data);

        if (!class_exists($data['type'])) {
            return $data['type'];
        }

        $this->verifyImplementation($data);

        return $data['type'];
    }

    /**
     * @param array $data
     *
     * @return array|null
     */
    private function validateFactoryData(array $data)
    {
        if (!isset($data['factory'])) {
            return null;
        }

        $factory = $data['factory'];

        if (is_string($factory)) {
            $this->verifyCallback($factory);

            return $factory;
        }

        return $this->processArrayFactory($factory);
    }

    /**
     * @param array $data
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    private function verifyType(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException("Property 'type' must be defined for the 'IdType' annotation.");
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @throws \InvalidArgumentException
     */
    private function verifyImplementation(array $data)
    {
        $refClass = new \ReflectionClass($data['type']);

        if (!$refClass->implementsInterface('Ident\IdentifiesObjects')) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Class '%s' must implement 'Ident\\IdentifiesObjects'.",
                    $data['type']
                )
            );
        }
    }

    /**
     * @param $factory
     *
     * @throws \InvalidArgumentException
     */
    private function verifyCallback($factory)
    {
        if (!is_callable($factory)) {
            throw new \InvalidArgumentException(
                sprintf(
                    "Property 'factory' of annotation 'IdType' of type string with value '%s' must be a callable",
                    $factory
                )
            );
        }
    }

    /**
     * @param array $factory
     *
     * @return array
     */
    private function processArrayFactory($factory)
    {
        $factory = (array) $factory;

        $defaultFactory = [
            'service' => null,
            'params'  => [],
            'method'  => null
        ];

        return array_merge($defaultFactory, $factory);
    }
}
