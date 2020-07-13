<?php

namespace App\Controller;

use App\Controller\DTO\PersonDTO;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Service\PersonService;

/**
 * @Route("/person")
 */
class PersonController extends AbstractController
{

    /** @var PersonService */
    private $service;

    public function __construct(PersonService $service)
    {
        $this->service = $service;
    }

    /**
     * @Route("/list", name="list people")
     */
    public function findAllPeople()
    {
        $people = $this->service->findAll();
        return $this->render('person/list.html.twig', ['people' => $people]);
    }

    /**
     * @Route("/form", name="person_form")
     */
    public function form()
    {
        return $this->render('person/form.html.twig');
    }

    /**
     * @Route("/", methods={"POST"}, name="create")
     * @ParamConverter("person", converter="fos_rest.request_body")
     */
    public function createOrUpdate(PersonDTO $person, Request $request)
    {
        $success = $this->service->createOrUpdate($person);
        return $this->json(['success' => $success]);
    }

    /**
     * @Route("/people/games/{game}")
     */
    public function findPeopleWhoPlayGame($game)
    {
        $people = $this->service->findByGame($game);
        return $this->json($people);
    }

    /**
     * @Route("/people/{id}")
     */
    public function getPersonDetails($id)
    {
        $person = $this->service->findById($id);
        return $this->json($person);
    }

    /**
     * @Route("/games/rename", methods={"POST"})
     */
    public function renameGame(Request $req)
    {
        $data = json_decode($req->getContent(), true);

        $success = $this->service->renameGame($data['oldName'], $data['newName']);

        return $this->json(['success' => $success]);
    }
}
