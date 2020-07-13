<?php

namespace App\Repository;

use App\Entity\PersonEntity;
use Elasticsearch\ClientBuilder;

class PersonRepository {

    const REFRESH_INTERVAL = 1; // in seconds
    const INDEX = 'people';

    private $client;

    function __construct() 
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['elastic:9200'])
            ->build();
    }

    public function createIndex()
    {
        $refreshInterval = self::REFRESH_INTERVAL > 0
            ? self::REFRESH_INTERVAL . 's'
            : -1;
        $params = [
            'index' => self::INDEX,
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 1,
                    'refresh_interval' => $refreshInterval 
                ]
            ]
        ];
        $this->client->indices()->create($params);
    }

    public function deleteIndex()
    {
        $params = [
            'index' => self::INDEX
        ];
        $this->client->indices()->delete($params);
    }

    /**
     * @return PersonEntity[]
     */
    public function findAll() 
    {
        $query = [
            'match_all' => new \stdClass()
        ];
        return $this->doSearch($query);
    }

    public function createOrUpdate(PersonEntity $person) 
    {
        $params = [
            'index' => self::INDEX,
            'id' => $person->id,
            'body' => $person->toArray()
        ];
        
        $result = $this->client->index($params);

        $this->waitRefreshInterval();

        return $result['_shards']['failed'] == 0;
    }

    public function updateGameName($oldName, $newName)
    {

        $script = '
            List g = new ArrayList(); 
            for (p in ctx._source.games) { 
                if (p == "'.$oldName.'")  { 
                    g.add("'.$newName.'") 
                } else {
                    g.add(p)
                } 
                ctx._source.games = g; 
            }
        ';

        $params = [
            'index' => self::INDEX,
            'body' => [
                'script' => [
                    'lang' => 'painless',
                    'source' => $script
                ],
                'query' => [
                    'match' => [
                        'games' => $oldName
                    ]
                ]
            ]
        ];

        $result = $this->client->updateByQuery($params);
        
        $this->waitRefreshInterval();
        
        return $result['updated'];
    }

    /**
     * @return PersonEntity
     */
    public function findById($id) 
    {
        $params = [
            'index' => self::INDEX,
            'id' => $id
        ];

        $result = $this->client->get($params);

        return PersonEntity::fromArray($result['_source']);
    }

    /**
     * @return PersonEntity[]
     */
    public function findByGame($game)
    {
        $query = [
            'match' => [
                'games' => $game
            ]
        ];
        return $this->doSearch($query);
    }

    public function deleteById($id)
    {
        $params = [
            'index' => self::INDEX,
            'id' => $id
        ];

        $result = $this->client->delete($params);

        $this->waitRefreshInterval();

        return $result['_shards']['failed'] == 0;
    }

    /**
     * @return PersonEntity[]
     */
    private function doSearch(array $query)
    {
        $params = [
            'index' => self::INDEX,
            'body' => [
                '_source' => true,
                'query' => $query
            ]
        ];

        $result = $this->client->search($params);

        $people = array();
        foreach ($result['hits']['hits'] as $hit) {
            $people[] = PersonEntity::fromArray($hit['_source']);
        }
        
        return $people;
    }

    private function waitRefreshInterval()
    {
        if (self::REFRESH_INTERVAL > 0)
        {
            sleep(self::REFRESH_INTERVAL);
        }
    }

}