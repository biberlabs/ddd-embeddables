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

class ColorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider badHexCodes
     * @expectedException InvalidArgumentException
     */
    public function testInvalidHexNotAccepted($hex)
    {
        $hex = new Color($hex);
    }

    /**
     * @dataProvider goodHexCodes
     */
    public function testAlwaysNormalizeHexValues($val, $normalized)
    {
        $color = new Color($val);
        $this->assertEquals($normalized, (string) $color);
    }

    public function testRGBConversionWorks()
    {
        $color = new Color('FFF');
        $this->assertEquals([255, 255, 255], $color->toRGB());
        $this->assertEquals('rgb(255,255,255)', $color->toRGBString());
        $this->assertInstanceOf(\JsonSerializable::class, $color);
        $this->assertEquals($color->jsonSerialize(), $color->toArray());
    }

    public function testFactory()
    {
        $color = Color::fromRGB(255, 255, 255);
        $this->assertEquals('#FFFFFF', $color->toHex());
    }

    public function testEmptyState()
    {
        $color = new Color();
        $this->assertInstanceOf(Color::class, $color);
        $this->assertEquals([], $color->toArray());
        $this->assertEquals([], $color->toRGB());
        $this->assertEquals('', (string) $color);
    }

    /**
     * @dataProvider sampleColorNames
     */
    public function testSerializedColorHasAName($hex, $name)
    {
        $color = new Color($hex);
        $arr   = $color->toArray();
        $this->assertEquals($arr['name'], $name);
    }

    public function goodHexCodes()
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

    public function badHexCodes()
    {
        return [
            ['#FFFA'],
            ['#CACAC'],
            ['TXDFA8'],
            ['#TXDFA8'],
            [false],
            [-1],
            ['rgb(1,2,3)'],
            ['Hello'],
        ];
    }

    public function sampleColorNames()
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
