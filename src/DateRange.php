<?php
/**
 * Daterange value object to represent date periods.
 * 
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace DDD\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class DateRange implements JsonSerializable
{
    /**
     * start date
     * 
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @var \DateTime
     */
    private $dateFrom;

    /**
     * end date
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTime
     */
    private $dateTo;

    /**
     * Constructor
     * 
     * @param \DateTime $start
     * @param \DateTime $end
     */
    public function __construct(\DateTime $start = null, \DateTime $end = null)
    {
        if ($start === null || $end === null) {
            return;
        } elseif ($start >= $end) {
            throw new \InvalidArgumentException('Start date is greater or equal to end date');
        }
        
        $this->dateFrom = $start;
        $this->dateTo   = $end;
    }

    /**
     * Gets the start date.
     *
     * @return \DateTime
     */
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * Gets the end date.
     *
     * @return \DateTime
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * Formats date range to string using given $format
     * 
     * @param string $f Any format accepted by php date()
     * 
     * @return string
     */
    public function format($f = 'c')
    {
        if ($this->isEmpty()) {
            return '';
        }

        return $this->getDateFrom()->format($f).' - '.$this->getDateTo()->format($f);
    }

    /**
     * String representation of date range.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->format('c');
    }

    /**
     * Returns duration of the date range in seconds.
     * 
     * @return int
     */
    public function getDurationInSeconds()
    {
        if (!$this->dateFrom) {
            return 0;
        }

        $interval = $this->dateFrom->diff($this->dateTo);

        return ($interval->y * 365 * 24 * 60 * 60) +
               ($interval->m * 30 * 24 * 60 * 60) +
               ($interval->d * 24 * 60 * 60) +
               ($interval->h * 60 * 60) +
               ($interval->i * 60) +
               $interval->s;
    }

    /**
     * Array representation of the range
     * 
     * @return array
     */
    public function toArray($format = 'c')
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            'start' => $this->getDateFrom()->format($format),
            'end'   => $this->getDateTo()->format($format),
        ];
    }

    /**
     * Implement json serializable interface.
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Returns a boolean TRUE if the range instance is
     * literally empty, FALSE otherwise.
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return !$this->dateFrom || !$this->dateTo;
    }
}
