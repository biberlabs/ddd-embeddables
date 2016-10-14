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
        $this->assertEquals($geoPoint->toArray(), ['lat' => 24, 'lng' => 34]);
        $this->assertEquals($geoPoint->jsonSerialize(), ['lat' => 24, 'lng' => 34]);
        $this->assertEquals($geoPoint->toElastic(), ['lat' => 24, 'lon' => 34]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidGeoPoint()
    {
        $geoPoint = new GeoPoint(-300, 300);
    }

    public function testEmptyState()
    {
        $p = new GeoPoint();
        $this->assertInstanceOf(GeoPoint::class, $p);
        $this->assertEquals([], $p->toArray());
        $this->assertEquals([], $p->toElastic());
    }

    public function testStringRepresentationAndEmptyState()
    {
        $point = new GeoPoint(41.520112, 29.453401);
        $this->assertEquals((string) $point, '41.520112 29.453401');

        $point = new GeoPoint();
        $this->assertEquals(null, (string) $point);
        $this->assertInternalType('array', $point->toArray());
    }

    public function testElasticsearchSupport()
    {
        $point   = new GeoPoint(41, 29);
        $elastic = ['lat' => 41, 'lon' => 29];
        $this->assertEquals($elastic, $point->toElastic());
        $this->assertNotEquals($elastic, $point->toArray());
    }
}
