# Color Value Object
`DDD\Embeddebles\Color` is a simple value object, it helps developer when persisting and using color information application-wide, in DDD way.

An example use case, there is a `Product` entity:

```
<?php

namespace MyApp\Entity;

use Doctrine\ORM\Mapping as ORM;
use DDD\Embeddable\Color;

/**
 * @ORM\Entity
 */
class Product
{
    private $id;
    private $name;

    /**
     * @ORM\Embedded(class="DDD\Embeddable\Color")
     */
    private $color;
}
```

If `$color` property of the `Product` is an embeddable `Color` value object
instead of a simple string, we'll gain a sort of developer and domain friendly
advantages on implementation time such as:

```
$prod = new Product();
$prod->setColor(new Color('#FF00FF'));
// or
$prod->setColor(new Color('F0F'));
// or
$prod->setColor(Color::fromRGB(255,0,255));

$name       = $prod->getName();
$background = $prod->getColor()->toHex();
$label      = $prod->getColor()->getName(); // "Fuchsia"

// Directly convert to json
$json      = json_encode($prod->getColor());
// {"hex":"#FF00FF","rgb":[255,0,255],"name":"Fuchsia"}

$rgb = $prod->getColor()->toRGB(); // [255,0,255]
```