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
        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findAll();

        return $this->render('mechanic/index.html.twig', [
            'mechanics' => $mechanics,
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
    /**
     * @Route("/mechanic/edit/{id}", name="mechanic_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($id);

        return $this->render('mechanic/edit.html.twig', [
            'mechanic' => $mechanic,
        ]);
    }
    /**
     * @Route("/mechanic/update/{id}", name="mechanic_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {
        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($id);

        $mechanic->
        setName($r->request->get('mechanic_name'))->
        setSurname($r->request->get('mechanic_surname'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mechanic);
        $entityManager->flush();

        return $this->redirectToRoute('mechanic_index');
    }
    /**
     * @Route("/mechanic/delete/{id}", name="mechanic_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($id);

        // if ($mechanic->getOutfits()->count() > 0) {
        //     return new Response('Šio kūrėjo ištrinti negalima, nes turi gaminių.');
        // }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mechanic);
        $entityManager->flush();

        return $this->redirectToRoute('mechanic_index');
    }
}
