ident
=====

[![Build Status](https://scrutinizer-ci.com/g/tPl0ch/ident/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tPl0ch/ident/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tPl0ch/ident/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tPl0ch/ident/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tPl0ch/ident/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tPl0ch/ident/?branch=master)

A library to provide an interface to object "identities and identifiers".
###### Note
This documentation is still a work in progress and subject to change at any given time.

Features
--------

- POC of a common interface to object identity
- Various implementations of Identifiers (`UuidIdentifier`, `BinaryUuidIdentifier`, `StringIndentifier`, `NullIdentifier`)
- Annotation driven configuration (YAML and XML are in the pipeline) of object identifier(s) (requires `jms/metadata: >= 1.1` and `doctrine/annotations: >= 1.0`).
- Doctrine2 ORM integration (requires `doctrine/orm: >= 2.x`).
- `BinaryUuidType` DBAL Type (requires `doctrine/dbal: >= 2.5.x-dev`) to save some storage in the underlying database implementation (though  you should keep index fragmentation in mind when using this as a primary key).
- A `HashFactory` to easily generate unique hashes of various algorithms based of random byte data (requires `symfony/security: >=2.3`).


Introduction
------------

**Identity** is a very important concept in our every day lives.
If you imagine a class `Person`, it is clear that this `Person` must have an identity.

There is a vast variety of various implementations of `Identity`, and this library tries to provide 
a common **interface** to deal with this.

A `Person` **`HasIdentity`**:

```php
<?php
namespace Ident;

interface HasIdentity
{
    /**
     * This method returns the, in its context, unique identifier.
     *
     * @return IdentifiesObjects
     */
    public function getIdentifier();
}
```

An `Identifier` on the other hand **`IdentifiesObjects`**. Imagine this as a **signature** of the Identity. Or in a real world language a **Passport** or a **Driving License**.

```php
<?php
namespace Ident;

interface IdentifiesObjects
{
    /**
     * This method is a static factory that reconstitutes an identifier
     * from its signature.
     * 
     * @param string $signature
     * @return IdentifiesObjects
     * @throws \Ident\Exception\InvalidSignature
     */
    public static function fromSignature($signature);

    /**
     * This method returns a unique identity representation
     *
     * @return string
     */
    public function signature();

    /**
     * The __toString() method should also return the signature for
     * various compatibility reasons.
     *
     * @return string
     */
    public function __toString();
}
```
Of course, there needs to be some kind of `Registry`. Think of the city administration that registers you after you have moved to a new city.

A `Registry` **`RegistersIdentities`**:

```php
<?php
namespace Ident;

interface RegistersIdentities
{
    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function add(HasIdentity $identity);

    /**
     * @param HasIdentity $identity
     *
     * @return bool
     */
    public function contains(HasIdentity $identity);

    /**
     * @param HasIdentity $identity
     *
     * @return void
     */
    public function remove(HasIdentity $identity);

    /**
     * @param IdentifiesObjects $id
     *
     * @return HasIdentity $identity
     */
    public function get(IdentifiesObjects $id);

    /**
     * @return \Iterator
     */
    public function all();

    /**
     * @return void
     */
    public function clear();

    /**
     * @param callable $callable
     *
     * @return void
     */
    public function map(Callable $callable);

    /**
     * @param IdentifiesObjects $id
     *
     * @return bool
     */
    public function has(IdentifiesObjects $id);

    /**
     * @param IdentifiesObjects $id
     *
     * @return void
     */
    public function del(IdentifiesObjects $id);
}
```

Now, where does `Identity` come from? Do you inherit it automatically when i.e. you are born? In terms of administrative processes you are not yet identified until your parents would receive a **birth certificate**. This means there is some authority able to **issue** identities.

An `Issuer` **`CreatesIdentities`**:

```php
<?php
namespace Ident;

interface CreatesIdentities
{
    /**
     * This method issues an Identity for a given context.
     * The context can hold any arbitrary data, and its contents should
     * be validated within the underlying implementations.
     * 
     * @param mixed $context
     * @return \Ident\IdentifiesObjects
     * @throws \Ident\Exception\IdentityNotResolvable
     */
    public function identify($context);
}
```

OK, so far so good. Almost everybody was in some kind of authorative control, i.e. stopped by the **police** in the car because your lights were not working as they should. The authority needs to be able to compare **`Identities`** and **`Identfiers`**:

```php
<?php
namespace Ident;

interface IdentityCanBeCompared
{
    /**
     * This method compares itself to a given Identifier and returns
     * true or false if the Identifier is equal.
     *
     * @param IdentifiesObjects $id
     * @return bool
     */
    public function equals(IdentifiesObjects $id);
}
```

The same applies of course for objects. Imagine you looking at some photos of a gangster and say: "Yes, that's Thomas!"

```php
<?php
namespace Ident;

interface ObjectCanBeCompared
{
    /**
     * @param HasIdentity $object
     * @return bool
     */
    public function equals(HasIdentity $object);
}
```

Installation
------------

This package is installed via the [Composer](http://getcomposer.org/) PHP package manager.

```bash
php composer.phar require tp/ident "1.0.x-dev"
```
#### Requirements
- PHP 5.5+ (due to the use of generators)

Usage
-----

#### Using the `IdType` annotation:

The `IdType` annotations has two parameters:

- `type` (`string`) [required]:

  Either a mapped alias (see annotation processor configuration below for an 
  example) or a FQCN of a class implementing the `Ident\IdentifiesObjects` 
  interface.

- `factory` (`string|array`)
  * If `string`, a callable static method/function name is expected.
  * If `array`, the array has 3 keys:
    * `service`: The key of the service to be looked up in the `ServiceLocatorInterface` as a `string`
    * `method`: The method name to call on the given service as a `string`
    * `params`: The parameters for that method as `array`

##### Example

Given we have the following `Identifier`:

```php
<?php
namespace My\Orders;

use Ident\Identifiers\BinaryUuidIdentifier;

class OrderId extends BinaryUuidIdentifier
{
}
```

And the following Domain object implementing `Ident\HasIdentity`:

```php
<?php
namespace My\Orders;

use Ident\HasIdentity;
use Ident\Metadata\Annotation as Ident;

class Order implements HasIdentity
{
    /**
     * @Ident\IdType(
     *  type="My\Orders\OrderId",
     *  factory="Rhumsaa\Uuid\Uuid::uuid4"
     * )
     */
    private $identifier;

    /**
     * @Ident\IdType(
     *  type="Ident\Identifiers\StringIdentifier",
     *  factory={"service"="hash.factory", "method"="hash", "params"={"sha1"}}
     * )
     */
    private $transactionHash;

    /**
     * @return \Ident\IdentifiesObjects
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * @return \Ident\IdentifiesObjects
     */
    public function getTransactionHash()
    {
        return $this->transactionHash;
    }
}
```

Then the `Processor` will automatically set those values when called on the object:

```php
<?php
// Use the processor to identify objects with an annotation configuration
$domainObject = new My\Orders\Order(); // Has annotation config
$processor->identify($domainObject);

$domainObject->getIdentifier() instanceof My\Orders\OrderId; //true
$domainObject->getTransactionHash() instanceof Ident\Identifiers\StringIdentifier; //true

```

Configuration
-------------

This part shows some examples on how to integrate this library.

#### Creating the metadata `Processor`:

```php
<?php
// Autoload composer
require VENDOR_PATH . '/autoload.php';

// Register annotations
\Doctrine\Common\Annotations\AnnotationRegistry::registerFile(
    VENDOR_PATH . "/tp/ident/src/Metadata/Annotation/Mapping/IdentAnnotations.php");

// Create the driver
$driver = new \Ident\Metadata\Driver\AnnotationDriver(
    new \Doctrine\Common\Annotations\AnnotationReader()
);

// Create the ServiceLocator, there is also a Symfony ans a Pimple ServiceLocator implementation
$services = [];
$services['my.service'] = new \My\Awsome\Service();
$container = new \Ident\ServiceLocator\SimpleArrayServiceLocator($services);

// Create an AliasToIdentityMapper (optional)
$mapper = new \Ident\Mapper\InMemoryClassToIdentityMapper();
$mapper->register('string', 'Ident\Identifiers\StringIdentifier');
$mapper->register('uuid_string', 'Ident\Identifiers\UuidIdentifier');
$mapper->registerMany(
    [
        'my_type',
        'another_type'
    ],
    'My\Identifier\Implementation'
);

// Finally, create the metadata processor
$processor = \Ident\Metadata\Processor\IdentityMetadataProcessor(
    new \Metadata\MetadataFactory($driver),
    $container,
    $mapper
);

// Use the processor to identify objects with an annotation configuration
$domainObject = new Order(); // Has annotation config
$processor->identify($domainObject);
```

#### Enabling the Doctrine ORM integration:

```php
<?php
$em = $serviceLovator->get('doctrine.default_entity_manager');
$processor = $serviceLocator->get('ident.metadata.processor');
$em->getEventManager()
    ->addEventSubscriber(
        new IdentitySubscriber($processor)
    );
```

#### Enabling the Doctrine DBAL Uuid Types:

```php
<?php
\Doctrine\DBAL\Types\Type::addType(
    \Ident\Doctrine\Type\BinaryUuidType::NAME, // 'uuid_binary'
    '\Ident\Doctrine\Type\BinaryUuidType'
);

\Doctrine\DBAL\Types\Type::addType(
    \Ident\Doctrine\Type\UuidType::NAME, // 'uuid_string'
    '\Ident\Doctrine\Type\UuidType'
);
```
