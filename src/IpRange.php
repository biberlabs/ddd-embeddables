<?php
/**
 * IP range value object
 *
 * Thanks to Ross Tuck for this great article:
 * http://rosstuck.com/persisting-value-objects-in-doctrine/
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
class IpRange implements JsonSerializable
{
    /**
     * beginning IP address of the range
     *
     * @ORM\Embedded(class="DDD\Embeddable\IpAddress")
     */
    protected ?IpAddress $startIp = null;

    /**
     * high IP address of the range
     *
     * @ORM\Embedded(class="DDD\Embeddable\IpAddress")
     */
    protected ?IpAddress $endIp = null;

    public function __construct(IpAddress $startIp = null, IpAddress $endIp = null)
    {
        $this->startIp = $startIp;
        $this->endIp   = $endIp;
    }

    /**
     * Returns the low IP addresses of this range.
     */
    public function getStartIp() : ?IpAddress
    {
        return $this->startIp;
    }

    /**
     * Returns the high IP addresses of this range.
     */
    public function getEndIp() : ?IpAddress
    {
        return $this->endIp;
    }

    /**
     * Create a new range from CIDR notation.
     * CIDR notation is a compact representation of an IP address(es) and its associated routing prefix.
     */
    public static function fromCIDR(string $cidr) : IpRange
    {
        [$subnet, $bits] = explode('/', $cidr);
        $start               = long2ip((ip2long($subnet)) & ((-1 << (32 - (int)$bits))));
        $end                 = long2ip((ip2long($subnet)) + pow(2, (32 - (int)$bits))-1);

        return new IpRange(new IpAddress($start), new IpAddress($end));
    }

    /**
     * String representation of a range.
     *
     * Example output: "192.168.0.10 - 192.168.0.255"
     */
    public function __toString() : string
    {
        return $this->isEmpty() ? '' : $this->startIp . ' - ' . $this->endIp;
    }

    /**
     * Array representation of the ip range
     */
    public function toArray() : array
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            'startIp' => (string)$this->getStartIp(),
            'endIp' => (string)$this->getEndIp(),
        ];
    }

    /**
     * Returns boolean TRUE if the range is empty, false otherwise.
     */
    public function isEmpty() : bool
    {
        return $this->startIp === null || $this->endIp === null;
    }

    /**
     * Implement json serializable interface.
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}
