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

use DDD\Embeddable\Fullname;

class FullnameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider nameProvider
     *
     * @param string $name
     * @param string $title
     * @param string $midName
     * @param string $expected
     */
    public function testFullNameBuilderFlow($name, $title, $midName, $expected)
    {
        $obj = new Fullname($name, $title);
        $this->assertEquals($expected, (string) $obj);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidTitleIsNotAccepted()
    {
        $obj = new Fullname('John Doe', 'Mz.');
    }

    public function testGettersWorkingProperly()
    {
        $obj = new Fullname('Steven Paul Jobs', 'Mr');
        $this->assertEquals('Steven', $obj->getName());
        $this->assertEquals('Paul', $obj->getMiddleName());
        $this->assertEquals('Jobs', $obj->getSurname());
        $this->assertEquals('Mr.', $obj->getTitle());

        $obj2 = new Fullname("Nazim Hikmet Ran");
        $this->assertNull($obj2->getTitle());

        $expected = '{"title":null,"name":"Nazim","middleName":"Hikmet","surname":"Ran"}';
        $this->assertEquals($expected, json_encode($obj2));
    }

    public function testEmptyState()
    {
        $name = new Fullname();
        $this->assertInstanceOf(Fullname::class, $name);
        $this->assertEquals('', (string) $name);
    }

    /**
     * @return array
     */
    public function nameProvider()
    {
        return [
            ['Steve Jobs', 'Mr', null, 'Mr. Steve Jobs'],               // title without dot
            ['Richard', null, null, 'Richard'],                         // Just one name
            ['Steven Paul Jobs', null, 'Paul', 'Steven Paul Jobs'],     // with a middle name
            ['John D. Carmack', null, 'D.', 'John D. Carmack'],
            ['Steven   Paul   Jobs', null, 'Paul', 'Steven Paul Jobs'], // multiple spaces
            ['Steve Jobs', null, null, 'Steve Jobs'],                   // name and surname
            ['Steve Jobs', 'Mr.', null, 'Mr. Steve Jobs'],              // title with a dot
            // nickname as middlename
            ['Richard "rms" Stallman', null, '"rms"', 'Richard "rms" Stallman'],
            // Real name of Audrey Hepburn which is really long
            ['Edda Kathleen van Heemstra Hepburn', null, 'Kathleen van Heemstra', 'Edda Kathleen van Heemstra Hepburn'],
            [' Steve Wozniak ', null, null, 'Steve Wozniak'],           // Trailing slashes
            ['', null, null, ''],                                       // Empty string
        ];
    }
}
