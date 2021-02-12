<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Mechanic;

class MechanicController extends AbstractController
{
    /**
     * @Route("/mechanic", name="mechanic_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $mechanics = $this->getDoctrine()
        ->getRepository(Mechanic::class);
        if('name_az' == $r->query->get('sort')) {
            $mechanics = $mechanics->findBy([],['name' => 'asc', 'surname' => 'asc']);
        }
        else if('name_za' == $r->query->get('sort')) {
            $mechanics = $mechanics->findBy([],['name' => 'desc']);
        }
        else if('surname_az' == $r->query->get('sort')) {
            $mechanics = $mechanics->findBy([],['surname' => 'asc']);
        }
        else if('surname_za' == $r->query->get('sort')) {
            $mechanics = $mechanics->findBy([],['surname' => 'desc']);
        }
        else {
            $mechanics = $mechanics->findAll();
        }

        return $this->render('mechanic/index.html.twig', [
            'mechanics' => $mechanics,
            'sortBy' => $r->query->get('sort') ?? 'default',
            'sortBy' => $r->query->get('sort') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/mechanic/create", name="mechanic_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $mechanic_name = $r->getSession()->getFlashBag()->get('mechanic_name', []);
        $mechanic_surname = $r->getSession()->getFlashBag()->get('mechanic_surname', []);

        return $this->render('mechanic/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'mechanic_name' => $mechanic_name[0] ?? '',
            'mechanic_surname' => $mechanic_surname[0] ?? ''
        ]);
    }
    /**
     * @Route("/mechanic/store", name="mechanic_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        if (!$this->isCsrfTokenValid('', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('mechanic_create');
        } 
        
        $mechanic= New Mechanic;
        $mechanic->
        setName($r->request->get('mechanic_name'))->
        setSurname($r->request->get('mechanic_surname'));

        $errors = $validator->validate($mechanic);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('mechanic_name', $r->request->get('mechanic_name'));
            $r->getSession()->getFlashBag()->add('mechanic_surname', $r->request->get('mechanic_surname'));

            return $this->redirectToRoute('mechanic_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mechanic);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Mechanic successfully added.');

        return $this->redirectToRoute('mechanic_index');
    }
    /**
     * @Route("/mechanic/edit/{id}", name="mechanic_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($id);
        $mechanic_name = $r->getSession()->getFlashBag()->get('mechanic_name', []);
        $mechanic_surname = $r->getSession()->getFlashBag()->get('mechanic_surname',[]);

        return $this->render('mechanic/edit.html.twig', [
            'mechanic' => $mechanic,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'mechanic_name' => $mechanic_name[0] ?? '',
            'mechanic_surname' => $mechanic_surname[0] ?? ''
        ]);
    }
    /**
     * @Route("/mechanic/update/{id}", name="mechanic_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $mechanic = $this->getDoctrine()
        ->getRepository(Mechanic::class)
        ->find($id);

        $mechanic->
        setName($r->request->get('mechanic_name'))->
        setSurname($r->request->get('mechanic_surname'));

        $errors = $validator->validate($mechanic);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('mechanic_name', $r->request->get('mechanic_name'));
            $r->getSession()->getFlashBag()->add('mechanic_surname', $r->request->get('mechanic_surname'));

            return $this->redirectToRoute('mechanic_edit', ['id' => $mechanic->getId()]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($mechanic);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Mechanic successfully edited.');

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

        if ($mechanic->getTrucks()->count() > 0) {
            return new Response('Šio mechaniko ištrinti negalima, nes turi gaminių.');
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mechanic);
        $entityManager->flush();

        return $this->redirectToRoute('mechanic_index');
    }
}
