<?php
/**
 * Unit tests for Color value object.
 *
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Oct 2016
 */

namespace Test\Embeddable;

use DDD\Embeddable\Color;
use InvalidArgumentException;
use JsonSerializable;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    /**
     * @dataProvider badHexCodes
     */
    public function testInvalidHexNotAccepted(mixed $hex) : void
    {
        $this->expectException(InvalidArgumentException::class);
        new Color($hex);
    }

    /**
     * @dataProvider goodHexCodes
     */
    public function testAlwaysNormalizeHexValues(string $val, string $normalized) : void
    {
        $color = new Color($val);
        $this->assertSame($normalized, (string)$color);
    }

    public function testRGBConversionWorks() : void
    {
        // Given
        $underTest = new Color('FFF');

        // When then
        $this->assertSame([255, 255, 255], $underTest->toRGB());
        $this->assertSame('rgb(255,255,255)', $underTest->toRGBString());
        $this->assertInstanceOf(JsonSerializable::class, $underTest);
        $this->assertSame($underTest->jsonSerialize(), $underTest->toArray());
    }

    public function testFromRgb() : void
    {
        $actual = Color::fromRGB(255, 255, 255)->toHex();
        $this->assertSame('#FFFFFF', $actual);
    }

    public function testEmptyState() : void
    {
        // Given
        $underTest = new Color();

        // When then
        $this->assertSame([], $underTest->toArray());
        $this->assertSame([], $underTest->toRGB());
        $this->assertSame('', (string)$underTest);
    }

    /**
     * @dataProvider sampleColorNames
     */
    public function testSerializedColorHasAName(string $hex, string $expectedName) : void
    {
        $arr = (new Color($hex))->toArray();
        $this->assertSame($expectedName, $arr['name']);
    }

    public function goodHexCodes() : array
    {
        return [
            ['#CCA', '#CCCCAA'],
            ['#cca', '#CCCCAA'],
            ['#ccccaa', '#CCCCAA'],
            ['CCA', '#CCCCAA'],
            ['c9a', '#CC99AA'],
            ['aba', '#AABBAA'],
            ['000', '#000000'],
            ['9A4', '#99AA44'],
        ];
    }

    public function badHexCodes() : array
    {
        return [
            ['#FFFA'],
            ['#CACAC'],
            ['TXDFA8'],
            ['#TXDFA8'],
            [-1],
            ['rgb(1,2,3)'],
            ['Hello'],
        ];
    }

    public function sampleColorNames() : array
    {
        return [
            ['CCCCCC', 'Pastel Gray'],
            ['FF0000', 'Red'],
            ['0F0', 'Electric Green'],
            ['00F', 'Blue'],
            ['FF00FF', 'Fuchsia'],
            ['FE00FE', 'Fuchsia'],
        ];
    }
}
