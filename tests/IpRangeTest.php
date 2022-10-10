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

use DDD\Embeddable\IpRange;
use PHPUnit\Framework\TestCase;

class IpRangeTest extends TestCase
{
    /**
     * @dataProvider cidrList
     */
    public function testCIDRNotationWorks(string $cidr, string $expectedLow, string $expectedHigh) : void
    {
        // Given
        $underTest = IpRange::fromCIDR($cidr);
        $expected = [
            'startIp' => $expectedLow,
            'endIp' => $expectedHigh,
        ];

        // When
        $actual = $underTest->toArray();
        $this->assertSame($expected, $actual);

        // Then
        $this->assertSame($expectedLow, (string)$underTest->getStartIp());
        $this->assertSame($expectedHigh, (string)$underTest->getEndIp());
    }

    public function testEmptyState() : void
    {
        // Given
        $underTest = new IpRange();

        // When
        $actual = $underTest->toArray();

        // Then
        $this->assertSame([], $actual);
        $this->assertSame('', (string)$underTest);
        $this->assertTrue($underTest->isEmpty());
    }

    private function cidrList() : array
    {
        return [
            ['176.240.112.0/24', '176.240.112.0', '176.240.112.255'],
            ['176.240.112.0/32', '176.240.112.0', '176.240.112.0'],
            ['10.0.0.0/22', '10.0.0.0', '10.0.3.255'],
        ];
    }
}
