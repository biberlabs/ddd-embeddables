<?php
/**
 * Email address value object
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
class EmailAddress
{
    /**
     * email adress
     * 
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     * @var string
     */
    private $address;

    /**
     * Constructor.
     * 
     * @param string $email E-mail address
     */
    public function __construct($email)
    {
        // This is only a soft validation.
        // You SHOULD sanitize & validate email before using it as a value object!
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Given e-mail address '.$email.' is not a valid');
        }

        $this->address = $email;
    }

    /**
     * String representation of a email.
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->address;
    }

    /**
     * Returns domain part of the email address like gmail.com, yahoo.com etc.
     * 
     * @return string
     */
    public function getDomain()
    {
        return explode('@', $this->address)[1];
    }

    /**
     * Returns local part of the email address
     * 
     * @return string
     */
    public function getLocalPart()
    {
        return explode('@', $this->address)[0];
    }
}
