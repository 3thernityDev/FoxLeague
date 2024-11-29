<?php

namespace App\Controller;

use App\Repository\TeamRepository;
use App\Repository\MissionRepository;
use App\Repository\PowerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(MissionRepository $missionRepository, PowerRepository $powerRepository, TeamRepository $teamRepository): Response
    {
        // Récupérer les 5 dernières missions
        $missions = $missionRepository->findBy([], ['createDate' => 'DESC'], 5);

        // Récupérer tous les pouvoirs
        $powers = $powerRepository->findAll();

        // Organiser les pouvoirs par rareté
        $groupedPowers = [];
        foreach ($powers as $power) {
            $rarity = $power->getRarity();
            $groupedPowers[$rarity][] = $power;
        }

        // Récupérer toutes les équipes et trier par taux de succès (par exemple)
        $teams = $teamRepository->findBy([], ['successRate' => 'DESC']);

        // Sélectionner les 3 meilleures équipes
        $topTeams = array_slice($teams, 0, 3);

        return $this->render('home_page/index.html.twig', [
            'missions' => $missions,
            'groupedPowers' => $groupedPowers,
            'topTeams' => $topTeams,  // Passer les 3 meilleures équipes
        ]);
    }
}
