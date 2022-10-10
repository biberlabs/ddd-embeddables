<?php
/**
 * Represents a latitude and longitude pair.
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
class GeoPoint implements JsonSerializable
{
    /**
     * geo point value, stored as associative array
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private array $point = [];

    public function __construct(float $latitude = null, float $longitude = null)
    {
        if ($latitude && $longitude) {
            if ($latitude < -90.0 || $latitude > 90.0 || $longitude < -180.0 || $longitude > 180.0) {
                throw new InvalidArgumentException('Given latitude longitude pair is invalid.');
            }

            $this->point = [
                'lat' => $latitude,
                'lng' => $longitude,
            ];
        }
    }

    /**
     * return elasticsearch-friendly geo point format.
     *
     * Beware: Elastic uses lat/lon as keys. Google uses lat/lng.
     */
    public function toElastic() : array
    {
        if (empty($this->point)) {
            return [];
        }

        return [
            'lat' => $this->point['lat'],
            'lon' => $this->point['lng'],
        ];
    }

    /**
     * Array representation of the geo point
     */
    public function toArray() : array
    {
        return $this->point;
    }

    /**
     * Implement json serializable interface.
     */
    public function jsonSerialize() : array
    {
        return $this->toArray();
    }

    /**
     * String representation of the point, lat lng order, separated by a space.
     */
    public function __toString(): string
    {
        if (empty($this->point)) {
            return '';
        }

        return $this->point['lat'].' '.$this->point['lng'];
    }
}
