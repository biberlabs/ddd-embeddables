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

class IpRangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider cidrList
     *
     * @param string $cidr
     * @param string $low
     * @param string $high
     */
    public function testCIDRNotationWorks($cidr, $low, $high)
    {
        $range = IpRange::createFromCidrNotation($cidr);
        $this->assertEquals((string) $range->getStartIp(), $low);
        $this->assertEquals((string) $range->getEndIp(), $high);
        $this->assertInstanceOf(IpRange::class, $range);

        $obj = json_decode(json_encode($range));
        $this->assertEquals($obj->startIp, (string) $range->getStartIp());
        $this->assertArrayHasKey('startIp', $range->toArray());
        $this->assertArrayHasKey('endIp', $range->toArray());
    }

    /**
     * @return array
     */
    public function cidrList()
    {
        return [
            ['176.240.112.0/24', '176.240.112.0', '176.240.112.255'],
            ['10.0.0.0/22', '10.0.0.0', '10.0.3.255'],
        ];
    }
}
