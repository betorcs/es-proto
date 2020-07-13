<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PeopleControllerTest extends WebTestCase {

    public function testPersonCreation()
    { 
        $client = static::createClient();

        $json = '{"id": 1, "name": "John", "birthday": "1990-01-02", "games": ["CoC"], "location": {"lat": -22, "lon": -49}}';
        $client->request('POST', '/person/', [], [], ['CONTENT_TYPE' => 'application/json'], $json);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        
    }

}