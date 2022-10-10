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

use DateTime;
use DDD\Embeddable\DateRange;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DateRangeTest extends TestCase
{
    public function testDateRanges() : void
    {
        // Given
        $start     = new DateTime('31 December 2016');
        $end       = new DateTime('1 January 2017');
        $startStr  = '2016-12-31T00:00:00+00:00';
        $endStr    = '2017-01-01T00:00:00+00:00';

        // When
        $underTest = new DateRange($start, $end);

        // Then
        $this->assertSame('2016 - 2017', $underTest->format('Y'));
        $this->assertSame('2016-12 - 2017-01', $underTest->format('Y-m'));
        $this->assertStringStartsWith('2016-12-31', $underTest->format());
        $this->assertSame(86400, $underTest->getDurationInSeconds());
        $this->assertSame(['start' => $startStr, 'end' => $endStr], $underTest->toArray());
        $this->assertSame(['start' => $startStr, 'end' => $endStr], $underTest->jsonSerialize());
        $this->assertSame($start->format('c') . ' - ' . $end->format('c'), (string)$underTest);
    }

    public function testEmptyState() : void
    {
        $underTest = new DateRange();
        $this->assertInstanceOf(DateRange::class, $underTest);
        $this->assertSame([], $underTest->toArray());
        $this->assertSame('', (string)$underTest);
        $this->assertSame(0, $underTest->getDurationInSeconds());
    }

    public function testOlderDateRages() : void
    {
        // Given
        $start = new DateTime('23 April 1923');
        $end   = new DateTime('24 April 1923');

        // When
        $actual = (new DateRange($start, $end))->getDurationInSeconds();

        // Then
        $this->assertSame(86400, $actual);
    }

    public function testEqualDatesNotAccepted() : void
    {
        $this->expectException(InvalidArgumentException::class);

        // same dates
        $start = new DateTime('23 April 1923');
        $end   = new DateTime('23 April 1923');

        new DateRange($start, $end);
    }

    public function testEndDateShouldGreaterThanStartDate() : void
    {
        $this->expectException(InvalidArgumentException::class);

        // end date is before the start date
        $start = new DateTime('23 April 1923');
        $end   = new DateTime('21 April 1923');

        new DateRange($start, $end);
    }
}
