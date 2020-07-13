<?php

namespace App\Entity;

class LocationEntity {

    var $lat;
    var $lon;

    function __construct($lat, $lon)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }

    public function toArray() 
    {
        return [
            'coordinates' => [$this->lat, $this->lon]
        ];
    }

    static function fromArray(array $data)
    {
        $coord = $data['coordinates'];
        return new LocationEntity($coord[0], $coord[1]);
    }

}