<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mechanic;

class MechanicController extends AbstractController
{
    /**
     * @Route("/mechanic", name="mechanic_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('mechanic/index.html.twig', [
            'controller_name' => 'MechanicController',
        ]);
    }
    /**
     * @Route("/mechanic/create", name="mechanic_create", methods={"GET"})
     */
    public function create(): Response
    {
        return $this->render('mechanic/create.html.twig', [
        ]);
    }
    /**
     * @Route("/mechanic/store", name="mechanic_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        $mechanic= New Mechanic;
        $mechanic->
        setName($r->request->get('mechanic_name'))->
        setSurname($r->request->get('mechanic_surname'));


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mechanic);
        $entityManager->flush();

        return $this->redirectToRoute('mechanic_index');
    }
}
