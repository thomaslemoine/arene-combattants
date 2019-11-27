<?php

namespace App\Controller;

use App\Entity\Battle;
use App\Entity\Fighter;
use App\Repository\BattleRepository;
use App\Repository\FighterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiController extends AbstractController
{

    /**
     * @Route("/api/fighters", name="api_fighters")
     * @return JsonResponse
     */
    public function fighters(FighterRepository $fighterRepository)
    {
        $fighters = $fighterRepository->findAll();
        $datas = [];
        foreach ($fighters as $fighter) {

            $datas[] = [
                'id' => $fighter->getId(),
                'race' => [
                    'id' => $fighter->getType()->getId(),
                    'name' => $fighter->getType()->getName()
                ],
                'name' => $fighter->getName(),
                'strength' => $fighter->getStrength(),
                'pv' => $fighter->getPv(),
                'intelligence' => $fighter->getIntelligence(),
                'created_at' => $fighter->getCreatedAt(),
                'updated_at' => $fighter->getUpdatedAt(),
                'killed_at' => $fighter->getKilledAt(),
                'king' => $fighter->getKing(),
            ];
        }
        return new JsonResponse($datas);
    }

    /**
     * @Route("/api/battles", name="api_battles")
     *
     * @param BattleRepository $battleRepository
     * @return JsonResponse
     */
    public function battles(BattleRepository $battleRepository)
    {
        $battles = $battleRepository->findAll();
        $datas = [];
        foreach ($battles as $battle) {

            $combattants = [];

            foreach ($battle->getFighter() as $fighter) {
                $fighters[] = [
                    'id' => $fighter->getId(),
                    'nom' => $fighter->getName(),
                ];
            }
            $datas[] = [
                'id' => $battle->getId(),
                'zone' => [
                    'id' => $battle->getZone()->getId(),
                    'name' => $battle->getZone()->getName()
                ],
                'created_at' => $battle->getCreatedAt(),
                'resume' => $battle->getDescription(),
                'fighters' => $fighters,
            ];
        }
        return new JsonResponse($datas);
    }

    /**
     * @Route("/api/fighter/{id}", name="api_fighter", requirements={"id": "\d+"})
     *
     * @param FighterRepository $fighterRepository
     * @return JsonResponse
     */
    public function fighter(Request $request, FighterRepository $fighterRepository): JsonResponse
    {
        $id = $request->get('id');

        $fighter = $fighterRepository->find($id);
        if ($fighter === null) {
            throw new BadRequestHttpException();
        }

        $datas = [
            'id' => $fighter->getId(),
            'name' => $fighter->getName(),
            'strength' => $fighter->getStrength(),
            'pv' => $fighter->getPv(),
            'intelligence' => $fighter->getIntelligence(),
            'race' => [
                'id' => $fighter->getType()->getId(),
                'name' => $fighter->getType()->getName()
            ],
            'created_at' => $fighter->getCreatedAt(),
            'updated_at' => $fighter->getUpdatedAt(),
            'killed_at' => $fighter->getKilledAt()
        ];
        return new JsonResponse($datas);
    }

    /**
     * @Route("/api/battle/{id}", name="api_battle", requirements={"id": "\d+"})
     *
     * @param BattleRepository $battleRepository
     * @return JsonResponse
     */
    public function battle(Request $request, BattleRepository $battleRepository): JsonResponse
    {
        $id = $request->get('id');

        $battle = $battleRepository->find($id);
        if ($battle === null) {
            throw new BadRequestHttpException();
        }

        foreach ($battle->getFighter() as $fighter) {
            $fighters[] = [
                'id' => $fighter->getId(),
                'nom' => $fighter->getName(),
            ];
        }

        $datas[] = [
            'id' => $battle->getId(),
            'zone' => [
                'id' => $battle->getZone()->getId(),
                'name' => $battle->getZone()->getName()
            ],
            'created_at' => $battle->getCreatedAt(),
            'resume' => $battle->getDescription(),
            'fighters' => $fighters,
        ];
        return new JsonResponse($datas);
    }


}
