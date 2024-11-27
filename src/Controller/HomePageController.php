<?php

namespace App\Controller;

use App\Repository\MissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomePageController extends AbstractController
{
    #[Route('/', name: 'app_home_page')]
    public function index(MissionRepository $missionRepository): Response
    {
        // Récupérer les 5 dernières missions (triées par date de création décroissante)
        $missions = $missionRepository->findBy([], ['createDate' => 'DESC'], 5);

        return $this->render('home_page/index.html.twig', [
            'missions' => $missions,
        ]);
    }
}
