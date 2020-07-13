<?php

namespace App\Entity;

class PersonEntity {

    var $id;
    var $name;
    var $birthday;
    var $location;
    var $games;

    function __construct($id, $name, $birthday, LocationEntity $location, array $games) 
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->location = $location;
        $this->games = $games;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birthday' => $this->birthday,
            'location' => $this->location->toArray(),
            'games' => $this->games
        ];
    }

    static function fromArray(array $data)
    {
        $location = LocationEntity::fromArray($data['location']);
        return new PersonEntity($data['id'], $data['name'], $data['birthday'], $location, $data['games']);
    }

}