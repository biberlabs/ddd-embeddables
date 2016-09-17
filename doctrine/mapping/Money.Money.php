<?php
/**
 * This library doesn't contains a Money value object. However, we strongly recommend
 * using Money\Money (moneyphp/money) for such requirement.
 *
 * In this case, you'll need to tell doctrine to use Money\Money as embeddable in 
 * your domain entities. This is an example PHP metadata mapping driver chain 
 * configuration for moneyphp/money. You can also use XML mapping too.
 * 
 * To attach this mapping to your driver chain:
 * 
 *   $dir = ['vendor/biberlabs/ddd-embeddables/doctrine/mapping'];
 *   $chain->addDriver(new PhpMappingDriver($dir), 'Money\Money');
 */
$metadata->isEmbeddedClass = true;

$metadata->mapField(array(
    'fieldName' => 'amount',
    'type'      => 'integer',
    'nullable'  => true
));

$metadata->mapField(array(
    'fieldName' => 'currency',
    'type'      => 'string',
    'nullable'  => true,
    'length'    => 3,
));
