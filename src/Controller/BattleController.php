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
    public function new(Request $request, FighterRepository $fighterRepository, ZoneRepository $zoneRepository, BattleRepository $battleRepository): Response
    {
        $fighters = $fighterRepository->findAllFighterAlive();
        $zones = $zoneRepository->findAll();

        $nbAtttaquants = count($fighters);

        if ($nbAtttaquants === 1) {
            $fighters[0]->setKing(1);
            dump("Le grand du tournoi est " . $fighters[0]->getName());
            die();
        }

        /*
        if ($nbAtttaquants%2){
            dump("$nbAtttaquants impair");
            die();
        }*/

        $this->initialize($fighters, $zones);


        // on réinitialise les tableaux
        $tableau = null;
        $fighters = null;
        $zones = null;

        $this->addFlash('success', 'Les combats sont terminés !');

        dump('termine');

        return $this->render('battle/index.html.twig', [
            'battles' => $battleRepository->findAll(),
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
        if ($this->isCsrfTokenValid('delete' . $battle->getId(), $request->request->get('_token'))) {
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
    public function initialize($fighters, $zones)
    {

        foreach ($fighters as $fighter) {
            //La force de base est fixée à 10. Des multiplicateurs sont attribués :•Aux Nains : random entre 1,5 et 2
            if ($fighter->getType()->__toString() === 'Nain') {
                $fighter->setStrength($fighter->getStrength() * round(rand(15, 20) / 10));
            }
            //L'intelligence de base est fixée à 10. Des multiplicateurs sont attribués :•Aux Elfes : random entre 1,5 et 2
            //Les PV de base sont fixés à 50. Des multiplicateurs sont attribués :•Aux Elfes : random entre 1,5 et 2,5
            if ($fighter->getType()->__toString() === 'Elfe') {
                $fighter->setIntelligence($fighter->getIntelligence() * round(rand(15, 20) / 10));
                $fighter->setPv($fighter->getPv() * round(rand(15, 20) / 10));
            }
            //Les PV de base sont fixés à 50. Des multiplicateurs sont attribués :•Aux Trolls : random entre 2,3 et 3
            if ($fighter->getType()->__toString() === 'Troll') {
                $fighter->setPv($fighter->getPv() * round(rand(23, 30) / 10));
            }
        };


        $nbFighter = true;
        //on mélange 2 fois le tableau de combattants
        shuffle($fighters);
        shuffle($fighters);

        while ($nbFighter) {

            $battle = new Battle();

            $fighter1 = array_shift($fighters);
            $fighter2 = array_pop($fighters);

            $battle->addFighter($fighter1);
            $battle->addFighter($fighter2);

            //random de zone
            $randomInt = array_rand($zones);
            $battle->setZone($zones[$randomInt]);

            $tableau[] = $battle;

            if (count($fighters) < 2) {
                $nbFighter = false;
            };
        }


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
                    }
                    if ($combattants[1]->getType()->__toString() === 'Troll') {
                        //perte de 20% de leur PV
                        $combattants[1]->setPv(round(($combattants[1]->getPv()) * 0.8));
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


        //on lance le combat
        $this->startFight($tableau);

    }


    /**
     * @param ArrayCollection $tableau
     */
    public function startFight($tableau)
    {

        $description = "";

        foreach ($tableau as $battle) {

            $arrayFighters = $battle->getFighter();

            if ($arrayFighters[0]->getIntelligence() < $arrayFighters[1]->getIntelligence()) {
                dump('CAS 1');
                $count = 0;
                while (($arrayFighters[0]->getPv() > 0) && ($arrayFighters[1]->getPv() > 0)) {
                    if ($count %2 ){
                        $arrayFighters[0]->setPv($arrayFighters[0]->getPv() - round($arrayFighters[1]->getStrength() / 4));
                        $description .= ($arrayFighters[1]->getName() . ' inflige ' . round($arrayFighters[1]->getStrength() / 4) . ' PV à ' . $arrayFighters[0]->getName() . '<br>');
                    }
                    else{
                        $arrayFighters[1]->setPv($arrayFighters[1]->getPv() - round($arrayFighters[0]->getStrength() / 4));
                        $description .= ($arrayFighters[0]->getName() . ' inflige ' . round($arrayFighters[0]->getStrength() / 4) . ' PV à ' . $arrayFighters[1]->getName() . '<br>');
                    }
                    $count++;
                }

                //si le combattant 0 est mort alors le winner est :
                if ($arrayFighters[0]->getPv() <= 0) {
                    $description .= ($arrayFighters[1]->getName() . ' tue ' . $arrayFighters[0]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[1]->getName() . '</strong> <br>💀 ' . $arrayFighters[0]->getName() . ' <br>');
                    $arrayFighters[0]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[1]);
                } //si le combattant 1 est mort alors le winner est :
                elseif ($arrayFighters[1]->getPv() <= 0) {
                    $description .= ($arrayFighters[0]->getName() . ' tue ' . $arrayFighters[1]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[0]->getName() . '</strong> <br>💀 ' . $arrayFighters[1]->getName() . ' <br>');
                    $arrayFighters[1]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[0]);
                }

                $battle->setDescription($description);
                $battle->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($battle);
                $entityManager->persist($arrayFighters[1]);
                $entityManager->persist($arrayFighters[0]);

                $entityManager->flush();
                $description = "";
            }

            if ($arrayFighters[1]->getIntelligence() < $arrayFighters[0]->getIntelligence()) {
                dump('CAS 2');
                $count = 0;
                while (($arrayFighters[0]->getPv() > 0) && ($arrayFighters[1]->getPv() > 0)) {
                    if ($count %2 ) {
                        $arrayFighters[1]->setPv($arrayFighters[0]->getPv() - round($arrayFighters[0]->getStrength() / 4));
                        $description .= ($arrayFighters[0]->getName() . ' inflige ' . round($arrayFighters[0]->getStrength() / 4) . ' PV à ' . $arrayFighters[1]->getName() . '<br>');
                    }else{
                        $arrayFighters[0]->setPv($arrayFighters[0]->getPv() - round($arrayFighters[1]->getStrength() / 4));
                        $description .= ($arrayFighters[1]->getName() . ' inflige ' . round($arrayFighters[1]->getStrength() / 4) . ' PV à ' . $arrayFighters[0]->getName() . '<br>');
                    }
                    $count++;
                }

                //si le combattant 0 est mort alors le winner est :
                if ($arrayFighters[0]->getPv() <= 0) {
                    $description .= ($arrayFighters[1]->getName() . ' tue ' . $arrayFighters[0]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[1]->getName() . '</strong> <br>💀 ' . $arrayFighters[0]->getName() . ' <br>');
                    $arrayFighters[0]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[1]);
                } //si le combattant 1 est mort alors le winner est :
                elseif ($arrayFighters[1]->getPv() <= 0) {
                    $description .= ($arrayFighters[0]->getName() . ' tue ' . $arrayFighters[1]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[0]->getName() . '</strong> <br>💀 ' . $arrayFighters[1]->getName() . ' <br>');
                    $arrayFighters[1]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[0]);
                }

                $battle->setDescription($description);
                $battle->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($battle);
                $entityManager->persist($arrayFighters[1]);
                $entityManager->persist($arrayFighters[0]);

                $entityManager->flush();
                $description = "";
            }

            if ($arrayFighters[1]->getIntelligence() === $arrayFighters[0]->getIntelligence() ) {
                dump('CAS 3');
                $count = 0;
                while (($arrayFighters[0]->getPv() > 0) && ($arrayFighters[1]->getPv() > 0)) {
                    if ($count %2 ) {
                        $arrayFighters[1]->setPv($arrayFighters[0]->getPv() - round($arrayFighters[0]->getStrength() / 4));
                        $description .= ($arrayFighters[0]->getName() . ' inflige ' . round($arrayFighters[0]->getStrength() / 4) . ' PV à ' . $arrayFighters[1]->getName() . '<br>');
                    }else{
                        $arrayFighters[0]->setPv($arrayFighters[0]->getPv() - round($arrayFighters[1]->getStrength() / 4));
                        $description .= ($arrayFighters[1]->getName() . ' inflige ' . round($arrayFighters[1]->getStrength() / 4) . ' PV à ' . $arrayFighters[0]->getName() . '<br>');
                    }
                    $count++;
                }

                //si le combattant 0 est mort alors le winner est :
                if ($arrayFighters[0]->getPv() <= 0) {
                    $description .= ($arrayFighters[1]->getName() . ' tue ' . $arrayFighters[0]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[1]->getName() . '</strong> <br>💀 ' . $arrayFighters[0]->getName() . ' <br>');
                    $arrayFighters[0]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[1]);
                } //si le combattant 1 est mort alors le winner est :
                elseif ($arrayFighters[1]->getPv() <= 0) {
                    $description .= ($arrayFighters[0]->getName() . ' tue ' . $arrayFighters[1]->getName() . ', il remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[0]->getName() . '</strong> <br>💀 ' . $arrayFighters[1]->getName() . ' <br>');
                    $arrayFighters[1]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[0]);
                }

                $battle->setDescription($description);
                $battle->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($battle);
                $entityManager->persist($arrayFighters[1]);
                $entityManager->persist($arrayFighters[0]);

                $entityManager->flush();
                $description = "";
            }

            /*
            if (($arrayFighters[1]->getIntelligence() === $arrayFighters[0]->getIntelligence()) && ($arrayFighters[1]->getStrength() === $arrayFighters[0]->getStrength()) && ($arrayFighters[1]->getPv() === $arrayFighters[0]->getPv())) {
                dump('CAS 4 IDENTIQUE');

                $int = random_int(0, 1);
                $arrayFighters[$int]->setPv(0);

                //si le combattant 0 est mort alors le winner est :
                if ($arrayFighters[0]->getPv() <= 0) {
                    $description .= 'Les deux combatants sont identique on tire donc au sort celui qui va mourir <br>';
                    $description .= ($arrayFighters[1]->getName() . ' remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[1]->getName() . '</strong> <br>💀 ' . $arrayFighters[0]->getName() . ' <br>');
                    $arrayFighters[0]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[1]);
                } //si le combattant 1 est mort alors le winner est :
                elseif ($arrayFighters[1]->getPv() <= 0) {
                    $description .= 'Les deux combatants sont identique on tire donc au sort celui qui va mourir <br>';
                    $description .= ($arrayFighters[0]->getName() . ' remporte le match <br>');
                    $description .= ('<strong>🎊 ' . $arrayFighters[0]->getName() . '</strong> <br>💀 ' . $arrayFighters[1]->getName() . ' <br>');
                    $arrayFighters[1]->setKilledAt(new \DateTime());
                    $battle->setWinner($arrayFighters[0]);
                }


                $battle->setDescription($description);
                $battle->setCreatedAt(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($battle);
                $entityManager->persist($arrayFighters[1]);
                $entityManager->persist($arrayFighters[0]);

                $entityManager->flush();
                $description = "";
            }*/

        }

        //les vainqueurs se voient augmenter leurs stocks de PV de 50+10
        foreach ($tableau as $battle) {
            $winners[] = $battle->getWinner();
        }
        foreach ($winners as $winner) {
            $winner->setPv(50);
            $winner->setStrength(10);
            $winner->setIntelligence(10);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($winner);
            $entityManager->flush();
        }


    }

}