<?php

namespace App\Controller;

use App\Entity\Combattant;
use App\Form\CombattantType;
use App\Repository\CombattantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/combattant")
 */
class CombattantController extends AbstractController
{
    /**
     * @Route("/", name="combattant_index", methods={"GET"})
     */
    public function index(CombattantRepository $combattantRepository): Response
    {
        return $this->render('combattant/index.html.twig', [
            'combattants' => $combattantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="combattant_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $combattant = new Combattant();
        $form = $this->createForm(CombattantType::class, $combattant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($combattant);
            $entityManager->flush();

            return $this->redirectToRoute('combattant_index');
        }

        return $this->render('combattant/new.html.twig', [
            'combattant' => $combattant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="combattant_show", methods={"GET"})
     */
    public function show(Combattant $combattant): Response
    {
        return $this->render('combattant/show.html.twig', [
            'combattant' => $combattant,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="combattant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Combattant $combattant): Response
    {
        $form = $this->createForm(CombattantType::class, $combattant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('combattant_index');
        }

        return $this->render('combattant/edit.html.twig', [
            'combattant' => $combattant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="combattant_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Combattant $combattant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$combattant->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($combattant);
            $entityManager->flush();
        }

        return $this->redirectToRoute('combattant_index');
    }
}
