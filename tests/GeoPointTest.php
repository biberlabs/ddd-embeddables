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
use PHPUnit\Framework\TestCase;

class GeoPointTest extends TestCase
{
    public function testValidGeoPoint() : void
    {
        $underTest = new GeoPoint(24, 34);
        $this->assertSame(['lat' => 24.0, 'lng' => 34.0], $underTest->toArray());
        $this->assertSame(['lat' => 24.0, 'lng' => 34.0], $underTest->jsonSerialize());
        $this->assertSame(['lat' => 24.0, 'lon' => 34.0], $underTest->toElastic());
    }

    public function testInvalidGeoPoint() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        new GeoPoint(-300, 300);
    }

    public function testEmptyState() : void
    {
        $underTest = new GeoPoint();
        $this->assertInstanceOf(GeoPoint::class, $underTest);
        $this->assertSame([], $underTest->toArray());
        $this->assertSame([], $underTest->toElastic());
        $this->assertSame('', (string)$underTest);
    }

    public function testStringRepresentation() : void
    {
        $underTest = new GeoPoint(41.520112, 29.453401);
        $this->assertSame('41.520112 29.453401', (string)$underTest);
    }

    public function testToElastic() : void
    {
        // Given
        $underTest = new GeoPoint(41, 29);
        $expected  = ['lat' => 41.0, 'lon' => 29.0];

        // When
        $actual = $underTest->toElastic();

        // Then
        $this->assertSame($expected, $actual);
    }

    public function testToArray() : void
    {
        // Given
        $underTest = new GeoPoint(42, 30);
        $expected  = ['lat' => 42.0, 'lng' => 30.0];

        // When
        $actual = $underTest->toArray();

        // Then
        $this->assertSame($expected, $actual);
    }
}
