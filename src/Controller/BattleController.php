<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Fighter;
use App\Entity\Zone;
use App\Form\BattleType;
use App\Repository\BattleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
            $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
                ->enableExceptionOnInvalidIndex()
                ->getPropertyAccessor();
            $entityManager->flush();
            $fighters = $propertyAccessor->getValue($battle, 'fighter');
            $zone = $propertyAccessor->getValue($battle, 'zone');
            $this->fight($fighters, $zone);
            dump($fighters);
            die();


            return $this->redirectToRoute('battle_show');
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

    /**
     * @param ArrayCollection $fighters
     * @param Zone $zone
     */
    public function fight($fighters, $zone){

        foreach ($fighters as $fighter){

            //La force de base est fixée à 10. Des multiplicateurs sont attribués :•Aux Nains : random entre 1,5 et 2
            if($fighter->getType()->__toString() === 'Nain'){
                $fighter->setStrength($fighter->getStrength() * round(rand(15, 20) / 10));
            }
            //L'intelligence de base est fixée à 10. Des multiplicateurs sont attribués :•Aux Elfes : random entre 1,5 et 2
            //Les PV de base sont fixés à 50. Des multiplicateurs sont attribués :•Aux Elfes : random entre 1,5 et 2,5
            if($fighter->getType()->__toString() === 'Elfe'){
                $fighter->setIntelligence($fighter->getIntelligence() * round(rand(15, 20) / 10));
                $fighter->setPv($fighter->getPv() * round(rand(15, 20) / 10));
            }
            //Les PV de base sont fixés à 50. Des multiplicateurs sont attribués :•Aux Trolls : random entre 2,3 et 3
            if($fighter->getType()->__toString() === 'Troll'){
                $fighter->setPv($fighter->getPv() * round(rand(23, 30) / 10));
            }
        };

        switch ($zone->getName()){
            case 'Forêt':
                dump($fighters[0]->getType()->__toString());
                if ($fighters[0]->getType()->__toString() === 'Nain'){

                }

                dump($fighters[0]);
                dump('une foreetttt');
                break;
            case 'Désert' :
                dump('un desert');
                break;
            case 'Prairie':
                dump('une prairieee');
                break;
        }



    }
}
