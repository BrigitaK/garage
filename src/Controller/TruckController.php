<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Mechanic;
use App\Entity\Truck;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck_index", methods={"GET"})
     */
    public function index(): Response
    {
        $trucks = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->findAll();

        return $this->render('truck/index.html.twig', [
            'trucks' => $trucks,
        ]);
    }
    /**
     * @Route("/truck/create", name="truck_create", methods={"GET"})
     */
    public function create(): Response
    {
        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findAll();

        return $this->render('truck/create.html.twig', [
            'mechanics' => $mechanics,
        ]);
    }
    /**
     * @Route("/truck/store", name="truck_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        // $master = $this->getDoctrine()
        // ->getRepository(Master::class)
        // ->find($r->request->get('truck_master_id'));

        $truck = New Truck;
        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear($r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanicId($r->request->get('truck_mechanic_id'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($truck);
        $entityManager->flush();

        return $this->redirectToRoute('truck_index');
    }
    /**
     * @Route("/truck/edit/{id}", name="truck_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $truck = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findAll();

        return $this->render('truck/edit.html.twig', [
            'truck' => $truck,
            'mechanics' => $mechanics
        ]);
    }
       /**
     * @Route("/truck/update/{id}", name="truck_update", methods={"POST"})
     */
    public function update(Request $r, $id): Response
    {
        $truck = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        // $mechanic = $this->getDoctrine()
        //  ->getRepository(Mechanic::class)
        //  ->find($r->request->get('truck_master_id'));

        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear($r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanicId($r->request->get('truck_mechanic_id'));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($truck);
        $entityManager->flush();

        //grazinu redirect
        return $this->redirectToRoute('truck_index');
    }
      /**
     * @Route("/truck/delete/{id}", name="truck_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $truck = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($truck);
        $entityManager->flush();

        //grazinu redirect
        return $this->redirectToRoute('truck_index');
    }
}