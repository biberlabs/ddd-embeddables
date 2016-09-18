<?php
/**
 * Unit tests for value objects.
 *
 * @author  Haydar KULEKCI <haydarkulekci@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace Test\Embeddable;

use DDD\Embeddable\GeoPoint;

class GeoPointTest extends \PHPUnit_Framework_TestCase
{
    public function testValidGeoPoint()
    {
        $geoPoint = new GeoPoint(24, 34);
        $this->assertEquals($geoPoint->getPoint(), ['lat' => 24, 'lng' => 34]);
        $this->assertEquals($geoPoint->toArray(), ['lat' => 24, 'lng' => 34]);
        $this->assertEquals($geoPoint->jsonSerialize(), ['lat' => 24, 'lng' => 34]);
        $this->assertEquals((string) $geoPoint, '24 34');
        $this->assertEquals($geoPoint->toElastic(), ['lat' => 24, 'lon' => 34]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGeoPoint()
    {
        $geoPoint = new GeoPoint(-300, 300);
    }
}
