## DDD Embeddables
A collection of reusable value objects written in PHP and targeting versions 5.6 and above. Value objects are essential building blocks of **Domain Driven Design** approach and described by Martin Fowler in _P of EAA page 486_ as below:


> Value object is a small simple object, like money or a date range, whose equality isn't based on identity.
> - Martin Fowler

All classes in this library annotated as `ORM\Embeddable` with appropriately adjusted column attributes. This makes them ready to use in any project with Doctrine ORM as [Embeddables](http://doctrine-orm.readthedocs.io/projects/doctrine-orm/en/latest/tutorials/embeddables.html).

## Installation & Usage
Install the library using [composer](https://getcomposer.org).

```
 $ composer require biberlabs/ddd-embeddables
```

and use it in your entities:

```php
<?php

namespace MyApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use DDD\Embeddable\EmailAddress;

/**
 * @ORM\Entity
 */
class User {

    /**
     * @ORM\Embedded(class="DDD\Embeddable\EmailAddress")
     */
    private $email;

    // Returns an EmailAddress instance, not a string
    public function getEmail();

    // Use type-hinting if you need a setter
    public function setEmail(EmailAddress $email);
}
```

Afterwards, you can write DQL's based on your requirements something like:

```sql
    SELECT u FROM User u WHERE u.email = :email
    -- OR
    SELECT u FROM User u WHERE u.name.surname = :surname
    SELECT u FROM User u WHERE u.name.title = :title
```

## Running Tests
You can run unit tests locally via issuing the command below:

```
$ composer test
```

Please make sure that all test are green before creating a PR.

```
PHPUnit 5.1.6 by Sebastian Bergmann and contributors.

...........                 11 / 11 (100%)

Time: 269 ms, Memory: 7.25Mb

OK (39 tests, 71 assertions)
```

## Contributing
If you want to contribute to **ddd-embeddables** and make it better, your help is very welcome.

Please take a look our [CONTRIBUTING.md](CONTRIBUTING.md) before creating a pull request.

## Further Reading
Do you interested in Domain Driven Design? Here a some list of good resources to dig in-depth.

 - [Value Objects](http://martinfowler.com/bliki/ValueObject.html) - Martin Fowler
 - [Separating Concerns using Embeddables](http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/tutorials/embeddables.html) - Doctrine ORM documentation
 - [Domain-Driven Design in PHP](https://leanpub.com/ddd-in-php/read) - Leanpub 380 pages e-book.
 - [Agregate Componenet Pattern In Action](https://lostechies.com/jimmybogard/2009/02/05/ddd-aggregate-component-pattern-in-action/) - Another good article from 2009
 - [Domain Driven Design Concepts in ZF2](https://olegkrivtcov.wordpress.com/2014/03/22/domain-driven-design-ddd-concepts-in-zf2/) - An article written by Oleg Krivtsov in 2014
 - [Domain Driven Design Quickly](https://www.infoq.com/minibooks/domain-driven-design-quickly) - A quickly-readable minibook and introduction to the fundamentals of DDD by InfoQ.