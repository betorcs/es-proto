<?php

namespace App\Service;

use App\Controller\DTO\LocationDTO;
use App\Controller\DTO\PersonDTO;
use App\Entity\LocationEntity;
use App\Entity\PersonEntity;
use App\Repository\PersonRepository;

class PersonService
{

    private $repository;
    private $convertToDTO;

    function __construct(PersonRepository $repository)
    {
        $this->repository = $repository;

        $this->convertToDTO = function (PersonEntity $person) { 
            return $this->convertToDTO($person); 
        };
    }

    /**
     * @return PersonDTO[]
     */
    public function findAll()
    {
        $entities = $this->repository->findAll();
        return array_map($this->convertToDTO, $entities);
    }

    /**
     * @return PersonDTO[]
     */
    public function findByGame($game) 
    {
        $entities = $this->repository->findByGame($game);
        return array_map($this->convertToDTO, $entities);
    }

    /**
     * @return bool
     */
    public function createOrUpdate(PersonDTO $person)
    {
        return $this->repository->createOrUpdate($this->convertToEntity($person));
    }

    /**
     * @return bool
     */
    public function renameGame($oldName, $newGame)
    {
        return $this->repository->updateGameName($oldName, $newGame);
    }

    /**
     * @return PersonDTO
     */
    public function findById($id)
    {
        $entity = $this->repository->findById($id);
        return $this->convertToDTO($entity);
    }

    /**
     * @return PersonEntity
     */
    private function convertToEntity(PersonDTO $person)
    {
        $location = new LocationEntity($person->location->lat, $person->location->lon);
        return new PersonEntity($person->id, $person->name, $person->birthday, $location, $person->games);
    }

    /** 
     * @return PersonDTO
     */
    private function convertToDTO(PersonEntity $person)
    {
        $location = new LocationDTO();
        $location->lat = $person->location->lat;
        $location->lon = $person->location->lon;
        $dto = new PersonDTO();
        $dto->id = $person->id;
        $dto->name = $person->name;
        $dto->birthday = $person->birthday;
        $dto->location = $location;
        $dto->games = $person->games;
        return $dto;
    }
}
