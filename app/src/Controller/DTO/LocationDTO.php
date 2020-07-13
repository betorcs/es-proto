<?php

namespace App\Controller\DTO;

class LocationDTO {

    var $lat;
    var $lon;

    function __construct(float $lat = 0.0, float $lon = 0.0)
    {
        $this->lat = $lat;
        $this->lon = $lon;
    }
}