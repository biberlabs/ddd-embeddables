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
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class IpAddressTest extends TestCase
{
    public function testInvalidAddressesAreNotAccepted() : void
    {
        $this->expectException(InvalidArgumentException::class);
        new IpAddress('0.0.0');
    }

    /**
     * @dataProvider ipProvider
     */
    public function testValidAddressesAreAccepted(string $addr, string $expected) : void
    {
        $underTest = new IpAddress($addr);
        $this->assertSame($expected, (string)$underTest);
    }

    public function testEmptyState() : void
    {
        $underTest = new IpAddress();
        $this->assertSame('', (string)$underTest);
    }

    private function ipProvider() : array
    {
        return [
            ['127.0.0.1', '127.0.0.1'],
            ['192.168.1.1', '192.168.1.1'],
        ];
    }
}
