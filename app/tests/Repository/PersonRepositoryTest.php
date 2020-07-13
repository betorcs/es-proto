<?php

namespace App\Tests\Repository;

use App\Entity\LocationEntity;
use App\Entity\PersonEntity;
use App\Repositpry\PersonRepository;
use PHPUnit\Framework\TestCase;

class PersonRepositoryTest extends TestCase {

    /** @var PersonRepository */
    private $repository;

    /** @beforeClass */
    public static function setUpIndex()
    {
        $repository = new PersonRepository();
        $repository->createIndex();
    }

    /** @afterClass */
    public static function tearDownIndex()
    {
        $repository = new PersonRepository();
        $repository->deleteIndex();
    }

    /** @before */
    public function setupRepository() 
    {
        $this->repository = new PersonRepository();
    }

    
    public function testCreateOrUpdate() 
    {
        // Given
        $person = $this->createTestPerson();

        // When
        $result = $this->repository->createOrUpdate($person);

        // Then
        $this->assertTrue($result);
    }

    public function testFindById()
    {
        // Prepare 
        $person = $this->createTestPerson();
        
        // Given
        $id = $person->id;

        // When
        $entity = $this->repository->findById($id);

        // Then
        $this->assertNotNull($entity);
        $this->assertEquals($person->name, $entity->name);
    }

    public function testFindByGame()
    {
        // Prepare
        $person = $this->createTestPerson();
        
        // Given
        $game = 'CSGO';

        // When
        $entities = $this->repository->findByGame($game);

        // Then
        $this->assertCount(1, $entities);
        $this->assertEquals($person->name, $entities[0]->name);
    }

    public function testFindAll() 
    {
        // When
        $entities = $this->repository->findAll();

        // Then
        $this->assertNotEmpty($entities);
    }

    public function testUpdateGameName()
    {
        // Prepare
        $gameName = 'CoC';
        $person = new PersonEntity(10, 'John', '2000-01-01', new LocationEntity(20, 29), [$gameName]);
        $this->repository->createOrUpdate($person);

        // Given
        $oldName = $gameName;
        $newName = 'Clash Of Clans';

        // When
        $afected = $this->repository->updateGameName($oldName, $newName);

        // Then
        $this->assertEquals(1, $afected);
    }

    public function testDeleteById()
    {
        // Prepare
        $person = $this->createTestPerson();

        // Given
        $id = $person->id;

        // When
        $success = $this->repository->deleteById($id);

        // Then 
        $this->assertTrue($success);
    }


    private function createTestPerson()
    {
        $location = new LocationEntity(-22, -49);
        return new PersonEntity(1, 'Roberto', '1983-08-08', $location, ['CSGO', 'HalfLife']);
    }    

}