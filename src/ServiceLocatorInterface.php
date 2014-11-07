<?php

namespace Ident;

/**
 * Interface ServiceLocatorInterface
 */
interface ServiceLocatorInterface
{
    /**
     * @param string $serviceName
     * @param array  $params
     *
     * @return object
     */
    public function get($serviceName, array $params = []);
}
