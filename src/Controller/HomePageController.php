<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use App\Repository\PowerRepository; // Assurez-vous d'importer cette classe si elle est utilisée.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(MissionRepository $missionRepository, PowerRepository $powerRepository): Response
    {
        // Récupérer les 5 dernières missions (triées par date de création décroissante)
        $missions = $missionRepository->findBy([], ['createDate' => 'DESC'], 5);

        // Récupérer tous les pouvoirs
        $powers = $powerRepository->findAll();

        // Organiser les pouvoirs par rareté
        $groupedPowers = [];
        foreach ($powers as $power) {
            $rarity = $power->getRarity();
            $groupedPowers[$rarity][] = $power;
        }

        return $this->render('home_page/index.html.twig', [
            'missions' => $missions,
            'groupedPowers' => $groupedPowers,
        ]);
    }
}
