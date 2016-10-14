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
     * 
     * @var IpAddress
     */
    protected $startIp;

    /**
     * high IP address of the range
     *
     * @ORM\Embedded(class="DDD\Embeddable\IpAddress")
     * 
     * @var IpAddress
     */
    protected $endIp;

    /**
     * Constructor.
     * 
     * @param IpAddress $startIp
     * @param IpAddress $endIp
     */
    public function __construct(IpAddress $startIp = null, IpAddress $endIp = null)
    {
        $this->startIp = $startIp;
        $this->endIp   = $endIp;
    }

    /**
     * Returns the low IP addresses of this range.
     * 
     * @return IpAddress
     */
    public function getStartIp()
    {
        return $this->startIp;
    }

    /**
     * Returns the high IP addresses of this range.
     * 
     * @return IpAddress
     */
    public function getEndIp()
    {
        return $this->endIp;
    }

    /**
     * Create a new range from CIDR notation.
     * CIDR notation is a compact representation of an IP address(es)
     * and its associated routing prefix.
     * 
     * @static
     *
     * @param string $cidr
     * 
     * @return self
     */
    public static function createFromCidrNotation($cidr)
    {
        list($subnet, $bits) = explode('/', $cidr);
        $start               = long2ip((ip2long($subnet)) & ((-1 << (32 - (int)$bits))));
        $end                 = long2ip((ip2long($subnet)) + pow(2, (32 - (int)$bits))-1);

        return new IpRange(new IpAddress($start), new IpAddress($end));
    }

    /**
     * String representation of a range.
     * 
     * Example output: "192.168.0.10 - 192.168.0.255"
     *    
     * @return string
     */
    public function __toString()
    {
        return $this->isEmpty() ? '' : (string) $this->startIp.' - '. $this->endIp;
    }

    /**
     * Array representation of the ip range
     * 
     * @return array
     */
    public function toArray()
    {
        if ($this->isEmpty()) {
            return [];
        }

        return [
            'startIp' => (string) $this->getStartIp(),
            'endIp'   => (string) $this->getEndIp(),
        ];
    }

    /**
     * Returns boolean TRUE if the range is empty, false otherwise.
     * 
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->startIp === null || $this->endIp === null;
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
}
