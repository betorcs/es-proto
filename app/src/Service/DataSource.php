<?php

namespace App\Service;

use Elasticsearch\ClientBuilder;

class DataSource 
{

    const INDEX = "people";

    private $client;

    public function __construct() 
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['elastic:9200'])
            ->build();
    }

    private function parseHits($hits)
    {
        return array_map(function ( $item ) { return $item['_source']; }, $hits);
    } 

    public function getPeople()
    {
        $params = [
            'index' => self::INDEX,
            'body'   => [
                'query' => [
                    'match_all' => new \stdClass()
                ]
            ]
        ];
        
        $res = $this->client->search($params);

        return $this->parseHits($res['hits']['hits']);
    }

    public function addPerson($person) 
    {
        $params = [
            'index' => self::INDEX,
            'id'    => $person['id'],
            'body' => $person
        ];
        
        return $this->client->index($params);
    }

    public function getPerson($id) 
    {
        return $this->client->get([
            'index' => self::INDEX,
            'id' => $id
        ])['_source'];
    }

    public function findPeopleByGame($game) {
        $params = [
            'index' => self::INDEX,
            'body'   => [
                'query' => [
                    'match' => [
                        'games' => $game
                    ]
                ]
            ]
        ];
        
        $res = $this->client->search($params);

        return $this->parseHits($res['hits']['hits']);
    }

    public function renameGame($oldName, $newName) 
    {
        $params = [
            'index' => self::INDEX,
            'body' => [
                'script' => [
                    'source' => 'List g = new ArrayList(); for (p in ctx._source.games) { if (p == "'.$oldName.'")  { g.add("'.$newName.'") } else {g.add(p)} ctx._source.games = g; }',
                    'lang' => 'painless'
                ],
                'query' => [
                    'match' => [
                        'games' => $oldName
                    ]
                ]
            ]
        ];
        return $this->client->updateByQuery($params);
    }

}