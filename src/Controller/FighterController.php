<?php

namespace App\Controller;

use App\Entity\Fighter;
use App\Form\FighterType;
use App\Repository\FighterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Cocur\Slugify\Slugify;

/**
 * @Route("/fighter")
 */
class FighterController extends AbstractController
{
    /**
     * @Route("/", name="fighter_index", methods={"GET"})
     */
    public function index(FighterRepository $fighterRepository): Response
    {
        return $this->render('fighter/index.html.twig', [
            'fighters' => $fighterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fighter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $fighter = new Fighter();
        $slugify = new Slugify();
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fighter->setSlug($slugify->slugify($fighter->getName()));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fighter);
            $entityManager->flush();

            return $this->redirectToRoute('fighter_index');
        }

        return $this->render('fighter/new.html.twig', [
            'fighter' => $fighter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="fighter_show", methods={"GET"})
     */
    public function show(Fighter $fighter): Response
    {
        return $this->render('fighter/show.html.twig', [
            'fighter' => $fighter,
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="fighter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fighter $fighter): Response
    {
        $form = $this->createForm(FighterType::class, $fighter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fighter_index');
        }

        return $this->render('fighter/edit.html.twig', [
            'fighter' => $fighter,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="fighter_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Fighter $fighter): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fighter->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fighter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fighter_index');
    }
}
