<?php
/**
 * Unit tests for value objects.
 *
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */

namespace Test\Embeddable;

use DDD\Embeddable\FullName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FullNameTest extends TestCase
{
    /**
     * @dataProvider nameProvider
     */
    public function testFullNameBuilderFlow(?string $name, ?string $title, ?string $midName, ?string $expected) : void
    {
        $obj = new FullName($name, $title);
        $this->assertSame($expected, (string)$obj);
    }

    public function testInvalidTitleIsNotAccepted() : void
    {
        $this->expectException(InvalidArgumentException::class);
        new FullName('John Doe', 'Mz.');
    }

    /**
     * @throws \JsonException
     */
    public function testGettersWorkingProperly() : void
    {
        $obj = new FullName('Steven Paul Jobs', 'Mr');
        $this->assertSame('Steven', $obj->getName());
        $this->assertSame('Paul', $obj->getMiddleName());
        $this->assertSame('Jobs', $obj->getSurname());
        $this->assertSame('Mr.', $obj->getTitle());

        $obj2 = new FullName("Nazim Hikmet Ran");
        $this->assertNull($obj2->getTitle());

        $expected = '{"title":null,"name":"Nazim","middleName":"Hikmet","surname":"Ran"}';
        $this->assertSame($expected, json_encode($obj2, JSON_THROW_ON_ERROR));
    }

    public function testEmptyState() : void
    {
        $name = new FullName();
        $this->assertInstanceOf(FullName::class, $name);
        $this->assertSame('', (string)$name);
    }

    private function nameProvider() : array
    {
        return [
            'title without dot' => ['Steve Jobs', 'Mr', null, 'Mr. Steve Jobs'],
            'just one name' => ['Richard', null, null, 'Richard'],
            'with a middle name' => ['Steven Paul Jobs', null, 'Paul', 'Steven Paul Jobs'],
            'middle name with dots' => ['John D. Carmack', null, 'D.', 'John D. Carmack'],
            'multiple spaces' => ['Steven   Paul   Jobs', null, 'Paul', 'Steven Paul Jobs'],
            'name and surname only' => ['Steve Jobs', null, null, 'Steve Jobs'],
            'title with a dot' => ['Steve Jobs', 'Mr.', null, 'Mr. Steve Jobs'],
            'nickname as middle name' => ['Richard "rms" Stallman', null, '"rms"', 'Richard "rms" Stallman'],
            'real name of Audrey Hepburn which is really long' => [
                'Edda Kathleen van Heemstra Hepburn',
                'Mrs',
                'Kathleen van Heemstra',
                'Mrs. Edda Kathleen van Heemstra Hepburn'
            ],
            'trailing slashes' => [' Steve Wozniak ', null, null, 'Steve Wozniak'],
            'empty strings' => ['', null, null, ''],
        ];
    }
}
