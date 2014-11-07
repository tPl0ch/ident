<?php

// Paths
define('TESTS_PATH', __DIR__);
define('SRC_PATH', __DIR__ . '/../src');
define('TESTS_TEMP_DIR', __DIR__.'/temp');
define('VENDOR_PATH', realpath(__DIR__ . '/../vendor'));

// Autoload composer
require VENDOR_PATH . '/autoload.php';

/**
 * Class Loader
 */
class Loader {
    /**
     * @var array
     */
    protected $namespaces;

    /**
     * @param array $namespaces
     */
    public function __construct(array $namespaces) {
        $this->namespaces = $namespaces;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __invoke($name) {
        foreach ($this->namespaces as $namespace) {
            if (strpos($name, $namespace) === 0) {

                return true;
            }
        }

        return false;
    }
}


// Annotations
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    VENDOR_PATH . "/doctrine/orm/lib/Doctrine/ORM/Mapping/Driver/DoctrineAnnotations.php"
);

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(
    new Loader(
        [
            'Ident\Metadata\Annotation'
        ]
    )
);

$paths = [
    TESTS_PATH . '/Stubs'
];

$services = [];

$services['annotation.reader'] = new Doctrine\Common\Annotations\AnnotationReader();
$services['driver.annotation'] = new Doctrine\ORM\Mapping\Driver\AnnotationDriver(
    $services['annotation.reader'],
    $paths
);

// Doctrine DBAL bootstrap
\Doctrine\DBAL\Types\Type::addType(
    \Ident\Doctrine\Type\BinaryUuidType::NAME,
    '\Ident\Doctrine\Type\BinaryUuidType'
);

\Doctrine\DBAL\Types\Type::addType(
    \Ident\Doctrine\Type\UuidType::NAME,
    '\Ident\Doctrine\Type\UuidType'
);

// Doctrine ORM bootstrap

// the connection configuration
$dbParams = [
    'driver'   => 'pdo_sqlite',
    'user'     => 'ident',
    'password' => 'ident',
    'memory'   => true
];

$config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration($paths);
$config->setMetadataDriverImpl($services['driver.annotation']);

$services['doctrine.orm.configuration'] = $config;

$em = \Doctrine\ORM\EntityManager::create($dbParams, $config);
$tool = new \Doctrine\ORM\Tools\SchemaTool($em);
$classes = [
    $em->getClassMetadata('Ident\Test\Stubs\Order'),
    $em->getClassMetadata('Ident\Test\Stubs\Payment')
];
$tool->createSchema($classes);

$services['em'] = $em;

$mapper = new \Ident\Factory\InMemoryClassToIdentityMapper();
$mapper->register('string', 'Ident\Identifiers\StringIdentifier');
$mapper->register('uuid_string', 'Ident\Identifiers\UuidIdentifier');
$mapper->register('uuid_binary', 'Ident\Identifiers\BinaryUuidIdentifier');

$services['mapper'] = $mapper;

$services['hash.factory'] = new \Ident\Factory\HashFactory(
    new \Symfony\Component\Security\Core\Util\SecureRandom()
);

$_ENV['container'] = new \Ident\ServiceLocator\SimpleArrayServiceLocator($services);
