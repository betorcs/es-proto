<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Service\DataSource;

class PeopleController extends AbstractController 
{

    private $dataSource;

    public function __construct(DataSource $dataSource) 
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @Route("/people/list", name="list people")
     */
    public function list() 
    {
        $data = $this->dataSource->getPeople();
        return $this->json($data);
    }

    /**
     * @Route("/people/games/{game}")
     */
    public function peoplePlaysGame($game)
    {
        $data = $this->dataSource->findPeopleByGame($game);
        return $this->json($data);
    }

    /**
     * @Route("/people", methods={"PUT"})
     */
    public function add(Request $req) 
    {
        $person = json_decode($req->getContent(), true);
        $data = $this->dataSource->addPerson($person);
        return $this->json($data);
    }

    /**
     * @Route("/people/{id}")
     */
    public function detail($id) 
    {
        $data = $this->dataSource->getPerson($id);
        return $this->json($data);
    }

    /**
     * @Route("/games/rename", methods={"POST"})
     */
    public function renameGame(Request $req) 
    {
        $data = json_decode($req->getContent(), true);
        $data = $this->dataSource->renameGame($data['oldName'], $data['newName']);
        return $this->json($data);
    }
}