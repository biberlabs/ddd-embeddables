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
    public function testDateRanges()
    {
        $start    = new \DateTime('31 December 2016');
        $end      = new \DateTime('1 January 2017');
        $startStr = '2016-12-31T00:00:00+00:00';
        $endStr   = '2017-01-01T00:00:00+00:00';
        $obj      = new DateRange($start, $end);

        $this->assertEquals('2016 - 2017', $obj->format('Y'));
        $this->assertEquals('2016-12 - 2017-01', $obj->format('Y-m'));
        $this->assertStringStartsWith('2016-12-31', $obj->format());
        $this->assertEquals(86400, $obj->getDurationInSeconds());
        $this->assertEquals($obj->toArray(), ['start' => $startStr, 'end' => $endStr]);
        $this->assertEquals($obj->jsonSerialize(), ['start' => $startStr, 'end' => $endStr]);
        $this->assertEquals((string) $obj, $start->format('c') . ' - ' . $end->format('c'));
    }

    public function testEmptyState()
    {
        $obj = new DateRange();
        $this->assertInstanceOf(DateRange::class, $obj);
        $this->assertEquals([], $obj->toArray());
        $this->assertEquals('', (string) $obj);
        $this->assertEquals(0, $obj->getDurationInSeconds());
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
