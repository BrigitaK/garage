<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Mechanic;
use App\Entity\Truck;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findBy([],['name' => 'asc','surname' => 'asc']);

        $trucks = $this->getDoctrine()
        ->getRepository(Truck::class);
        if(null !== $r->query->get('mechanic_id')){
            $trucks = $trucks->findBy(['mechanic_id' => $r->query->get('mechanic_id')], ['maker' => 'asc']);
        }
        else {
            $trucks = $trucks->findAll();
        }
        return $this->render('truck/index.html.twig', [
            'trucks' => $trucks,
            'mechanics' => $mechanics,
            'mechanicId' => $r->query->get('mechanic_id') ?? 0,
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/truck/create", name="truck_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $truck_maker = $r->getSession()->getFlashBag()->get('truck_maker', []);
        $truck_plate = $r->getSession()->getFlashBag()->get('truck_plate', []);
        $truck_make_year = $r->getSession()->getFlashBag()->get('truck_make_year', []);
        $truck_mechanic_notices = $r->getSession()->getFlashBag()->get('truck_mechanic_notices', []);

        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findBy([],['name' => 'asc']);

        return $this->render('truck/create.html.twig', [
            'mechanics' => $mechanics,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'truck_maker' => $truck_maker[0] ?? '',
            'truck_plate' => $truck_plate[0] ?? '',
            'truck_make_year' => $truck_make_year[0] ?? '',
            'truck_mechanic_notices' => $truck_mechanic_notices[0] ?? ''
        ]);
    }
    /**
     * @Route("/truck/store", name="truck_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        if (!$this->isCsrfTokenValid('', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('truck_create');
        } 

        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find((int)$r->request->get('truck_mechanic_id'));

        $truck = New Truck;
        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear((int)$r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanic($mechanic);

        $errors = $validator->validate($truck);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('truck_maker', $r->request->get('truck_maker'));
            $r->getSession()->getFlashBag()->add('truck_plate', $r->request->get('truck_plate'));
            $r->getSession()->getFlashBag()->add('truck_make_year', $r->request->get('truck_make_year'));
            $r->getSession()->getFlashBag()->add('truck_mechanic_notices', $r->request->get('truck_mechanic_notices'));

            return $this->redirectToRoute('truck_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($truck);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck successfully added.');

        return $this->redirectToRoute('truck_index');
    }
    /**
     * @Route("/truck/edit/{id}", name="truck_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $truck = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->findBy([],['name' => 'asc']);

        $truck_maker = $r->getSession()->getFlashBag()->get('truck_maker', []);
        $truck_plate = $r->getSession()->getFlashBag()->get('truck_plate', []);
        $truck_make_year = $r->getSession()->getFlashBag()->get('truck_make_year', []);
        $truck_mechanic_notices = $r->getSession()->getFlashBag()->get('truck_mechanic_notices', []);

        return $this->render('truck/edit.html.twig', [
            'truck' => $truck,
            'mechanics' => $mechanics,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'truck_maker' => $truck_maker[0] ?? '',
            'truck_plate' => $truck_plate[0] ?? '',
            'truck_make_year' => $truck_make_year[0] ?? '',
            'truck_mechanic_notices' => $truck_mechanic_notices[0] ?? ''
        ]);
    }
       /**
     * @Route("/truck/update/{id}", name="truck_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $truck = $this->getDoctrine()
        ->getRepository(Truck::class)
        ->find($id);

        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($r->request->get('truck_mechanic_id'));

        $truck->
        setMaker($r->request->get('truck_maker'))->
        setPlate($r->request->get('truck_plate'))->
        setMakeYear($r->request->get('truck_make_year'))->
        setMechanicNotices($r->request->get('truck_mechanic_notices'))->
        setMechanic($mechanic);

        $errors = $validator->validate($truck);
        if (count($errors) > 0) {
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('truck_maker', $r->request->get('truck_maker'));
            $r->getSession()->getFlashBag()->add('truck_plate', $r->request->get('truck_plate'));
            $r->getSession()->getFlashBag()->add('truck_make_year', $r->request->get('truck_make_year'));
            $r->getSession()->getFlashBag()->add('truck_mechanic_notices', $r->request->get('truck_mechanic_notices'));

            return $this->redirectToRoute('truck_edit');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($truck);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Truck successfully edited.');

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