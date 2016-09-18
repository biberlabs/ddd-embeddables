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

use DDD\Embeddable\DateRange;

class DateRangeTest extends \PHPUnit_Framework_TestCase
{
    public function testDateRages()
    {
        $start = new \DateTime('31 December 2016');
        $end   = new \DateTime('1 January 2017');

        $obj   = new DateRange($start, $end);
        $this->assertEquals('2016 - 2017', $obj->format('Y'));
        $this->assertEquals('2016-12 - 2017-01', $obj->format('Y-m'));
        $this->assertStringStartsWith('2016-12-31', $obj->format());
        $this->assertEquals(86400, $obj->getDurationInSeconds());
        $this->assertEquals($obj->toArray(), ['start' => $start, 'end' => $end]);
        $this->assertEquals($obj->jsonSerialize(), ['start' => $start, 'end' => $end]);
        $this->assertEquals((string) $obj, $start->format('c') . ' - ' . $end->format('c'));
    }

    public function testOlderDateRages()
    {
        $start = new \DateTime('23 April 1923');
        $end   = new \DateTime('24 April 1923');
        $obj   = new DateRange($start, $end);
        $this->assertEquals(86400, $obj->getDurationInSeconds());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEqualDatesNotAccepted()
    {
        // same dates
        $start = new \DateTime('23 April 1923');
        $end   = new \DateTime('23 April 1923');
        $obj   = new DateRange($start, $end);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testEndDateShouldGreaterThanStartDate()
    {
        $start = new \DateTime('23 April 1923');
        $end   = new \DateTime('21 April 1923');
        $obj   = new DateRange($start, $end);
    }
}
