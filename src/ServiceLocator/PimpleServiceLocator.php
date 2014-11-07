<?php

namespace Ident\ServiceLocator;

use Ident\ServiceLocatorInterface;
use Pimple\Container;

/**
 * Class PimpleServiceLocator
 */
class PimpleServiceLocator implements ServiceLocatorInterface
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $pimple
     */
    public function __construct(Container $pimple)
    {
        $this->container = $pimple;
    }

    /**
     * @param string $serviceName
     * @param array  $params
     *
     * @return object
     */
    public function get($serviceName, array $params = [])
    {
        return $this->container[$serviceName];
    }
}
