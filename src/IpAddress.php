<?php
/**
 * IP address value object to represent an internet protocol address.
 * 
 * @see https://en.wikipedia.org/wiki/IPv6_address
 * 
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace DDD\Embeddable;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class IpAddress
{
    /**
     * ipv4 or v6 address
     *
     * @ORM\Column(type="string", length=45, nullable=true)
     * 
     * @var string
     */
    private $address;

    /**
     * Constructor
     * 
     * @see https://secure.php.net/manual/en/filter.filters.flags.php
     * 
     * @throws InvalidArgumentException
     *
     * @param string $addr IP address.
     */
    public function __construct($addr = null)
    {
        if (!$addr) {
            return; // early..
        }

        $filtered = filter_var($addr, FILTER_VALIDATE_IP);
        if ($filtered === false) {
            throw new \InvalidArgumentException('Given IP '.$addr.' is not a valid IP address');
        }

        $this->address = $filtered;
    }

    /**
     * String representation of ip address.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->address ?: '';
    }
}
