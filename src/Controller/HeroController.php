<?php

namespace App\Controller;

use App\Repository\HeroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hero', name: 'hero_')]
class HeroController extends AbstractController
{
    private HeroRepository $heroRepository;

    public function __construct(HeroRepository $heroRepository)
    {
        $this->heroRepository = $heroRepository;
    }

    // Routes pour voir toute les heros 
    #[Route('/', name: 'hero_list')]
    public function searchAll(): Response
    {
        $heros = $this->heroRepository->findAll();

        return $this->render('hero/index.html.twig', [
            'controller_name' => 'HeroController',
            'heros' => $heros

        ]);
    }
}
