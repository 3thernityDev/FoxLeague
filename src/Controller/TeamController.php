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

    // Route pour voir toutes les équipes
    #[Route('/', name: 'list')]
    public function list(): Response
    {
        // Récupérer toutes les équipes
        $teams = $this->teamRepository->findAll();

        return $this->render('team/index.html.twig', [
            'teams' => $teams,
        ]);
    }

    // Route pour voir une équipe
    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function showTeam(Team $team): Response
    {
        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
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

    // Route pour éditer une équipe
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Team $team): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request;

            $team->setName($data->get('name'))
                ->setSuccessRate((int) $data->get('succesRate'))
                ->setFailRate((int) $data->get('FailRate'))
                ->setPopularity($data->get('popularity'));

            $this->em->flush();

            $this->addFlash('success', 'Équipe modifiée avec succès !');

            return $this->redirectToRoute('team_list');
        }

        return $this->render('team/edit.html.twig', [
            'team' => $team,
        ]);
    }

    // Route pour supprimer une équipe
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Team $team): Response
    {
        $this->em->remove($team);
        $this->em->flush();

        $this->addFlash('success', 'Équipe supprimée avec succès !');
        return $this->redirectToRoute('team_list');
    }
}
