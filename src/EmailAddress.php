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
use InvalidArgumentException;

/**
 * @ORM\Embeddable
 */
class EmailAddress
{
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private ?string $address = null;

    public function __construct(string $email = null)
    {
        // This is only a soft validation to reduce headaches earlier.
        // You SHOULD sanitize & validate actual email according to your needs, before using it as a value object!
        if ($email && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Given e-mail address ' . $email . ' is not a valid');
        }

        $this->address = $email;
    }

    /**
     * String representation of an email.
     */
    public function __toString(): string
    {
        return $this->address ?: '';
    }

    /**
     * Returns domain part of the email address like gmail.com, yahoo.com etc.
     */
    public function getDomain() : ?string
    {
        return $this->address ? explode('@', $this->address)[1] : null;
    }

    /**
     * Returns local part of the email address
     */
    public function getLocalPart() : ?string
    {
        return $this->address ? explode('@', $this->address, 2)[0] : null;
    }
}
