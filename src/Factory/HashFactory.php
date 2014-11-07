<?php

namespace Ident\Factory;

use Symfony\Component\Security\Core\Util\SecureRandomInterface;

/**
 * Class HashFactory
 */
class HashFactory
{
    /**
     * @var SecureRandomInterface
     */
    protected $random;

    /**
     * @param SecureRandomInterface $random
     */
    public function __construct(SecureRandomInterface $random)
    {
        $this->random = $random;
    }

    /**
     * @param string $algorithm
     * @param int    $byteLength
     *
     * @return string
     */
    public function hash($algorithm, $byteLength = 64)
    {
        return hash($algorithm, $this->random->nextBytes($byteLength));
    }
}
