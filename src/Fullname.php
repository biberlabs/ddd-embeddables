<?php
/**
 * Fullname value object to represents a person's name 
 * including title, firstname, middlename and lastname.
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
class Fullname implements JsonSerializable
{
    /**
     * Simple honorific title enumaration.
     * 
     * It mainly helps keeping titles consistent and easily validating them.
     * Also very useful to populate title dropdowns.
     */
    const TITLES = [
        'Mr.',
        'Ms.',   // address for women regardless of their marital status
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
     * 
     * @var string
     */
    private $title;

    /**
     * first name of the person.
     * 
     * @ORM\Column(type="string", length=64, nullable=true)
     * 
     * @var string
     */
    private $name;

    /**
     * middle name of the person.
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     * 
     * @var string
     */
    private $middleName;

    /**
     * last name of the person.
     *
     * @ORM\Column(type="string", length=64, nullable=true)
     * 
     * @var string
     */
    private $surname;

    /**
     * String representation of a full name.
     * 
     * @return string
     */
    public function __toString()
    {
        $result = [$this->title, $this->name, $this->middleName, $this->surname];
        // Array filter removes empty items.
        return implode(' ', array_filter($result));
    }

    /**
     * Constructor.
     * 
     * @param string $fullname Space separated full name. Ex: Steven Paul Jobs
     * @param string $title
     */
    public function __construct($fullname = null, $title = null)
    {
        if($fullname) {
            $this->buildFromString($fullname);
        }

        if ($title) {
            $this->setTitle($title);
        }
    }

    /**
     * A private setter for title.
     * 
     * @throws \InvalidArgumentException
     * 
     * @param string $title
     */
    private function setTitle($title)
    {
        if (substr($title, -1) !== '.') {
            // Allow titles without dots too
            $title = $title.'.';
        }

        if (!in_array($title, self::TITLES)) {
            throw new \InvalidArgumentException('Given title is invalid: '.$title);
        }

        $this->title = $title;
    }

    /**
     * Set name, surname and last fields using.
     * 
     * @param string $fullname
     *
     * @return self
     */
    private function buildFromString($fullname)
    {
        $names = array_filter(explode(' ', $fullname)); // explode from spaces
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
                    $this->middleName = trim($this->middleName.' '.$word);
                }
            }

            ++$i;
        }

        return $this;
    }

    /**
     * Gets the honorific title prefixing person's name.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Gets the first name of the person.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the middle name of the person.
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Gets the last name of the person.
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Returns array representation of the full name.
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'title'      => $this->title,
            'name'       => $this->name,
            'middleName' => $this->middleName,
            'surname'    => $this->surname,
        ];
    }

    /**
     * Returns array representation of the full name.
     * 
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
