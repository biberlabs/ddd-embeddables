<?php
/**
 * Unit tests for IpAddress value object.
 * 
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace Test\Embeddable;

use DDD\Embeddable\IpAddress;

class IpAddressTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAddressesAreNotAccepted()
    {
        $addr = new IpAddress('0.0.0');
    }

    /**
     * @dataProvider ipProvider
     */
    public function testValidAddressesAreAccepted($addr, $expected)
    {
        $obj = new IpAddress($addr);
        $this->assertEquals($expected, (string) $obj);
    }

    /**
     * ip data provider
     * 
     * @return array
     */
    public function ipProvider()
    {
        return [
            ['127.0.0.1', '127.0.0.1'],
            ['192.168.1.1', '192.168.1.1'],
        ];
    }
}
