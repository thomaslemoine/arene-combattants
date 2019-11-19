<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Form\BattleType;
use App\Repository\BattleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/battle")
 */
class BattleController extends AbstractController
{
    /**
     * @Route("/", name="battle_index", methods={"GET"})
     */
    public function index(BattleRepository $battleRepository): Response
    {
        return $this->render('battle/index.html.twig', [
            'battles' => $battleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="battle_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $battle = new Battle();
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($battle);
            $entityManager->flush();

            return $this->redirectToRoute('battle_index');
        }

        return $this->render('battle/new.html.twig', [
            'battle' => $battle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="battle_show", methods={"GET"})
     */
    public function show(Battle $battle): Response
    {
        return $this->render('battle/show.html.twig', [
            'battle' => $battle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="battle_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Battle $battle): Response
    {
        $form = $this->createForm(BattleType::class, $battle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('battle_index');
        }

        return $this->render('battle/edit.html.twig', [
            'battle' => $battle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="battle_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Battle $battle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$battle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($battle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('battle_index');
    }
}
