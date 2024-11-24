<?php

namespace App\Controller;

use App\Entity\Team;
use App\Repository\HeroRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/team', name: 'team_')]
class TeamController extends AbstractController
{
    private TeamRepository $teamRepository;
    private EntityManagerInterface $em;

    public function __construct(TeamRepository $teamRepository, EntityManagerInterface $em)
    {
        $this->teamRepository = $teamRepository;
        $this->em = $em;
    }



    // Route pour crée une team
    #[Route('/new', name: 'new')]
    public function create(Request $request): Response
    {
        $team = new Team();


        if ($request->isMethod('POST')) {
            $data = $request->request;

            $team->setName($data->get('name'))
                ->setSuccessRate((int) $data->get('succesRate'))
                ->setFailRate((int) $data->get('FailRate'))
                ->setPopularity($data->get('popularity'));

            $this->em->persist($team);
            $this->em->flush();

            $this->addFlash('success', 'Team créé avec succès ! ');
        }

        return $this->render('team/new.html.twig', [
            'team' => $team,
        ]);
    }
}
