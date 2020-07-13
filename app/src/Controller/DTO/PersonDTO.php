<?php

namespace App\Controller\DTO;

use App\Controller\DTO\LocationDTO;

class PersonDTO {

    var $id;
    var $name;
    var $birthday;
    var $location;
    var $games;

    function __construct(int $id = 0, string $name = null, \DateTime $birthday = null, LocationDTO $location = null, array $games = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->location = $location;
        $this->games = $games;
    }
}