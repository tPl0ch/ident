<?php

namespace Ident\ServiceLocator;

use Ident\ServiceLocatorInterface;

/**
 * Class SimpleArrayServiceLocator
 */
class SimpleArrayServiceLocator implements ServiceLocatorInterface
{
    /**
     * @var array
     */
    protected $services;

    /**
     * @param array $services
     */
    public function __construct($services = [])
    {
        $this->services = $services;
    }

    /**
     * @param string $serviceName
     * @param array  $params
     *
     * @return object
     *
     * @throws \Exception
     */
    public function get($serviceName, array $params = [])
    {
        if (!isset($this->services[$serviceName])) {
            throw new \Exception(
                sprintf("Service '%s' not found", $serviceName)
            );
        }

        return $this->services[$serviceName];
    }
}
