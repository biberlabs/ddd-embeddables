<?php
/**
 * Represents a latitude and longitude pair.
 * 
 * @author  M. Yilmaz SUSLU <yilmazsuslu@gmail.com>
 * @license MIT
 *
 * @since   Sep 2016
 */
namespace DDD\Embeddable\GeoPoint;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class GeoPoint implements JsonSerializable
{
    /**
     * geo point value, stored as assoc array
     *
     * @ORM\Column(type="array", nullable=true)
     * 
     * @var array
     */
    private $point;

    /**
     * Constructor
     *
     * @param float $lat Latitude
     * @param float $lng Longitude
     */
    public function __construct($lat, $lng)
    {
        if ($lat < -90.0 || $lat > 90.0 || $lng < -180.0 || $lng > 180.0) {
            throw new \InvalidArgumentException('Given lat/lng pair is invalid.');
        }
        
        $this->point = [
            'lat' => (float) $lat,
            'lng' => (float) $lng,
        ];
    }

    /**
     * Gets the geo point value.
     *
     * @return array
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * return elasticsearch-friendly geo point format.
     * 
     * @return array
     */
    public function toElastic()
    {
        if (empty($this->point)) {
            return null;
        }

        // Beware:
        // Elastic uses lat/lon as keys. Google uses lat/lng.
        return [
            'lat' => $this->point['lat'],
            'lon' => $this->point['lng'],
        ];
    }

    /**
     * Array representation of the geo point
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->point;
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
     * String representation of the point, lat lng order, separated by a space.
     * 
     * @return string
     */
    public function __toString()
    {
        if (empty($this->point)) {
            return;
        }

        return $this->point['lat'].' '.$this->point['lng'];
    }
}
