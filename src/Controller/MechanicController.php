<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
