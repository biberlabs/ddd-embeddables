<?php
/**
 * Fullname value object to represent a person's name
 * including title, firstname, middle name and lastname.
 *
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */

namespace DDD\Embeddable;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class FullName implements JsonSerializable
{
    /**
     * Simple honorific title enumeration.
     *
     * It mainly helps keeping titles consistent and easily validating them.
     * Also, very useful to populate title dropdowns.
     */
    public const TITLES = [
        'Mr.',
        'Ms.',   // for women regardless of their marital status
        'Mrs.',
        'Miss',
        'Mx.',   // a title that does not indicate gender
        'Adv.',  // advocate
        'Capt.', // captain
        'Dr.',
        'Prof.', // professor
    ];

    /**
     * honorific title prefixing a person's name.
     *
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private ?string $title = null;

    /**
     * first name of the person.
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $name = null;

    /**
     * middle name of the person.
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $middleName = null;

    /**
     * last name of the person.
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private ?string $surname = null;

    /**
     * String representation of a full name.
     */
    public function __toString()
    {
        // Array filter removes empty items.
        return implode(' ', array_filter([$this->title, $this->name, $this->middleName, $this->surname]));
    }

    /**
     * @param string|null $fullName Space separated full name. Ex: Steven Paul Jobs
     */
    public function __construct(string $fullName = null, string $title = null)
    {
        if ($fullName) {
            $this->buildFromString($fullName);
        }

        if ($title) {
            $this->setTitle($title);
        }
    }

    /**
     * A private setter for title.
     *
     * @throws InvalidArgumentException
     */
    private function setTitle(string $title) : void
    {
        if (! str_ends_with($title, '.')) {
            // Allow titles without dots too
            $title .= '.';
        }

        if (! in_array($title, self::TITLES)) {
            throw new InvalidArgumentException('Given title is invalid: ' . $title);
        }

        $this->title = $title;
    }

    /**
     * Set name, surname and last fields using.
     */
    private function buildFromString(string $fullName) : void
    {
        $names = array_filter(explode(' ', $fullName)); // explode from spaces
        $i     = 1;
        $total = count($names);

        foreach ($names as $word) {
            // skip trailing/double spaces
            if ($i === 1) {
                $this->name = $word;
            } elseif ($i >= 2) {
                if ($i === $total) {
                    $this->surname = $word;
                } else {
                    // merge all middle names
                    $this->middleName = trim($this->middleName . ' ' . $word);
                }
            }

            ++$i;
        }
    }

    /**
     * Gets the honorific title prefixing person's name.
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }

    /**
     * Gets the first name of the person.
     */
    public function getName() : ?string
    {
        return $this->name;
    }

    /**
     * Gets the middle name of the person.
     */
    public function getMiddleName() : ?string
    {
        return $this->middleName;
    }

    /**
     * Gets the last name of the person.
     */
    public function getSurname() : ?string
    {
        return $this->surname;
    }

    /**
     * Returns array representation of the full name.
     */
    public function toArray() : array
    {
        return [
            'title' => $this->title,
            'name' => $this->name,
            'middleName' => $this->middleName,
            'surname' => $this->surname,
        ];
    }

    /**
     * Returns array representation of the full name.
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }
}
