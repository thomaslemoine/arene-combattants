<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Fighter;
use App\Entity\Zone;
use App\Form\BattleType;
use App\Repository\BattleRepository;
use App\Repository\FighterRepository;
use App\Repository\ZoneRepository;
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
    public function new(Request $request, FighterRepository $fighterRepository, ZoneRepository $zoneRepository): Response
    {
        $fighters = $fighterRepository->findAllFighterAlive();
        $zones = $zoneRepository->findAll();
//        $zone = $zoneRepository->findRandom();

//        dump($fighters);
//        dump($zone);

//        $battle = new Battle();


//        $entityManager = $this->getDoctrine()->getManager();
//
//        $entityManager->persist($battle);
//
//        $entityManager->flush();

        //TODO mmelanger le tableau
        $this->fight($fighters, $zones);

//        $form = $this->createForm(BattleType::class, $battle);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//
//            $entityManager->persist($battle);
//            $propertyAccessor = PropertyAccess::createPropertyAccessorBuilder()
//                ->enableExceptionOnInvalidIndex()
//                ->getPropertyAccessor();
//            $entityManager->flush();
//            $fighters = $propertyAccessor->getValue($battle, 'fighter');
//            $zone = $propertyAccessor->getValue($battle, 'zone');
//            $this->fight($fighters, $zone);
//            dump($fighters);
//            die();


        die();

        return $this->redirectToRoute('battle_show');
//        }
//
//        return $this->render('battle/new.html.twig', [
//            'battle' => $battle,
//            'form' => $form->createView(),
//        ]);
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
     * @param ArrayCollection $zones
     */
    public function fight($fighters, $zones){

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

        $nbFighter = true;

        while ($nbFighter){

            $battle = new Battle();

            $fighter1 = array_shift($fighters);
            $fighter2 = array_shift($fighters);

            $battle->addFighter($fighter1);
            $battle->addFighter($fighter2);

            //random de zone
            $randomInt = array_rand($zones);
            $battle->setZone($zones[$randomInt]);

            $tableau[] = $battle;

            // TODO A deplacer à la fin du combat
            /*
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($battle);

            $entityManager->flush();
            */

            if (count($fighters) < 2){ $nbFighter = false; };
        }

        dump($tableau);

        foreach ($tableau as $val) {

            $combattants = $val->getFighter();

            switch ($val->getZone()->__toString()) {
                case 'Forêt':
                    if ($combattants[0]->getType()->__toString() === 'Elfe') {
                        //bonus de force +3
                        $combattants[0]->setStrength(($combattants[0]->getStrength()) + 3);
                    }
                    if ($combattants[1]->getType()->__toString() === 'Elfe') {
                        //bonus de force +3
                        $combattants[1]->setStrength(($combattants[1]->getStrength()) + 3);
                    }
                    if ($combattants[0]->getType()->__toString() === 'Nain') {
                        //malus de force -2
                        $combattants[0]->setStrength(($combattants[0]->getStrength()) - 2);
                    }
                    if ($combattants[1]->getType()->__toString() === 'Nain') {
                        //malus de force -2
                        $combattants[1]->setStrength(($combattants[1]->getStrength()) - 2);
                    }
                    break;
                case 'Désert' :
                    if ($combattants[0]->getType()->__toString() === 'Troll') {
                        //perte de 20% de leur PV
                        $combattants[0]->setPv(round(($combattants[0]->getPv()) * 0.8));
                        dump($combattants[0]->getName());
                    }
                    if ($combattants[1]->getType()->__toString() === 'Troll') {
                        //perte de 20% de leur PV
                        $combattants[1]->setPv(round(($combattants[1]->getPv()) * 0.8));
                        dump($combattants[1]->getName());
                    }
                    break;
                case 'Prairie':
                    if ($combattants[0]->getType()->__toString() === 'Nain') {
                        //bonus de force +4
                        $combattants[0]->setStrength(($combattants[0]->getStrength()) + 4);
                    }
                    if ($combattants[1]->getType()->__toString() === 'Nain') {
                        //bonus de force +4
                        $combattants[1]->setStrength(($combattants[1]->getStrength()) + 4);
                    }
                    if ($combattants[0]->getType()->__toString() === 'Troll') {
                        //bonus de force +2
                        $combattants[0]->setStrength(($combattants[0]->getStrength()) + 2);
                    }
                    if ($combattants[1]->getType()->__toString() === 'Troll') {
                        //bonus de force +2
                        $combattants[1]->setStrength(($combattants[1]->getStrength()) + 2);
                    }
                    break;
            }
        }

        $this->start($tableau);

    }


    public function start($tableau){


        $tableau2 = $tableau;


        dump($tableau2);
        die();

        foreach ($tableau2 as $val2){

            $combattants = $val2->getFighter();

            /**
             *
             * PREMIER CAS
             *
             */


            // si le joueur 0 à moins d'intelligence, le joueur 1 attaque en premier
            if ($combattants[0]->getIntelligence() < $combattants[1]->getIntelligence())
            {

                dump($combattants[0]->getName() . ' < ' . $combattants[1]->getName() );

                //Le 1 attaque en premier
                //tant que le combattant 0 ou 1 n'est pas mort on attaque chacun son tour
                while (($combattants[0]->getPv() >= 0) && ($combattants[1]->getPv() >= 0))
                {

                    dump($combattants[0]->getPv());

                    $combattants[0]->setPv($combattants[0]->getPv() - $combattants[1]->getStrength());
                    $combattants[1]->setPv($combattants[1]->getPv() - $combattants[0]->getStrength());

                }

                //si le combattant 0 est mort alors le winner est :
                if ($combattants[0]->getPv() <= 0){
                    $combattants[0]->setKilledAt(new \DateTime());
                    $tableau2[0]->setWinnerId($combattants[1]);
                }
                //si le combattant 1 est mort alors le winner est :
                if ($combattants[1]->getPv() <= 0){
                    $combattants[1]->setKilledAt(new \DateTime());
                    $tableau2[0]->setWinnerId($combattants[0]);
                }

                //on enregistre le battle
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($combattants[1]);
                $entityManager->persist($combattants[0]);
                $entityManager->persist($tableau2[0]);

                $entityManager->flush();
            }

            /***
             * DEUXIEME CAS
             *
             */

            // si le joueur 1 à moins d'intelligence, le joueur 0 attaque en premier
            if ($combattants[1]->getIntelligence() < $combattants[0]->getIntelligence())
            {

                dump($combattants[1]->getName() . ' < ' . $combattants[0]->getName() );

                //Le 1 attaque en premier
                //tant que le combattant 0 ou 1 n'est pas mort on attaque chacun son tour
                while (($combattants[1]->getPv() >= 0) && ($combattants[0]->getPv() >= 0))
                {

                    dump($combattants[1]->getPv());

                    $combattants[1]->setPv($combattants[1]->getPv() - $combattants[0]->getStrength());
                    $combattants[0]->setPv($combattants[0]->getPv() - $combattants[1]->getStrength());

                }

                //si le combattant 0 est mort alors le winner est :
                if ($combattants[0]->getPv() <= 0){
                    $combattants[0]->setKilledAt(new \DateTime());
                    $tableau2[0]->setWinnerId($combattants[1]);
                }
                //si le combattant 1 est mort alors le winner est :
                if ($combattants[1]->getPv() <= 0){
                    $combattants[1]->setKilledAt(new \DateTime());
                    $tableau2[0]->setWinnerId($combattants[0]);
                }

                //on enregistre le battle
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($combattants[1]);
                $entityManager->persist($combattants[0]);
                $entityManager->persist($tableau2[0]);

                $entityManager->flush();
            }



        }

    }
}
