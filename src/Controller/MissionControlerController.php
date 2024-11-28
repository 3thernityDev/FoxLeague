<?php

namespace App\Controller;

use App\Entity\Mission;
use App\Repository\MissionRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/mission', name: 'mission_')]
class MissionControlerController extends AbstractController
{
    private TeamRepository $teamrepository;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, TeamRepository $teamRepository)
    {
        $this->teamrepository = $teamRepository;
        $this->em = $em;
    }

    // Liste des missions
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(MissionRepository $missionRepository): Response
    {
        $missions = $missionRepository->findAll();

        return $this->render('mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }
    // Voir les détails d'une mission
    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', [
            'mission' => $mission,
        ]);
    }


    // Créer une mission
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $mission = new Mission();
        $teams = $this->teamrepository->findAll(); // Récupère toutes les équipes disponibles

        if ($request->isMethod('POST')) {
            $data = $request->request;

            // Récupère l'équipe sélectionnée ou null si aucune n'est choisie
            $teamId = $data->get('team');
            $team = $teamId ? $this->teamrepository->find($teamId) : null;

            // Validation de l'équipe
            if ($team && $team->getMission()) { // Vérifie si l'équipe est déjà assignée
                $this->addFlash('error', 'Équipe invalide ou déjà assignée à une mission.');
                return $this->redirectToRoute('mission_new');
            }

            // Remplit les champs de la mission
            $mission->setName($data->get('name'))
                ->setDescription($data->get('description'))
                ->setDurer((int) $data->get('durer'))
                ->setStatus($data->get('status'))
                ->setCreateDate(new \DateTime())
                ->setTeam($team); // Associe l'équipe à la mission (null si aucune)

            // Persiste et enregistre la mission dans la base de données
            $this->em->persist($mission);
            $this->em->flush();

            $this->addFlash('success', 'Mission créée avec succès !');
            return $this->redirectToRoute('mission_list');
        }

        // Affiche le formulaire pour créer une mission
        return $this->render('mission/new.html.twig', [
            'teams' => $teams,
        ]);
    }


    // Modifier une mission
    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission): Response
    {
        // Vérifie si la mission existe
        if (!$mission) {
            $this->addFlash('error', 'Mission introuvable.');
            return $this->redirectToRoute('mission_list');
        }

        // Récupère toutes les équipes disponibles pour affichage
        $teams = $this->teamrepository->findAll();

        // Vérifie si une requête POST a été soumise
        if ($request->isMethod('POST')) {
            $data = $request->request;

            // Récupère l'équipe sélectionnée ou null si aucune n'est choisie
            $teamId = $data->get('team');
            $team = $teamId ? $this->teamrepository->find($teamId) : null;

            // Si l'équipe est assignée à une autre mission
            if ($team && $team->getMission() && $team->getMission() !== $mission) {
                $this->addFlash('error', 'Équipe invalide ou déjà assignée à une autre mission.');
                return $this->redirectToRoute('mission_edit', ['id' => $mission->getId()]);
            }

            // Met à jour les champs de la mission
            $mission->setName($data->get('name'))
                ->setDescription($data->get('description'))
                ->setDurer((int) $data->get('durer'))
                ->setStatus($data->get('status'))
                ->setTeam($team); // Met à jour l'équipe associée (null si aucune)

            // Enregistre les modifications dans la base de données
            $this->em->flush();

            $this->addFlash('success', 'Mission modifiée avec succès !');
            return $this->redirectToRoute('mission_list');
        }

        // Affiche le formulaire avec les données de la mission
        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'teams' => $teams,
        ]);
    }

    // Route pour supprimer une mission
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Mission $mission): Response
    {
        if ($this->isCsrfTokenValid('delete' . $mission->getId(), $request->request->get('_token'))) {
            $team = $mission->getTeam();

            // Supprime le lien entre l'équipe et la mission sans supprimer l'équipe
            if ($team) {
                $mission->setTeam(null); // Retire le lien de la mission
                $team->setMission(null); // Retire le lien de l'équipe (sécurité)
                $this->em->persist($team);
            }

            // Supprime uniquement la mission
            $this->em->remove($mission);
            $this->em->flush();

            $this->addFlash('success', 'Mission supprimée avec succès !');
        }

        return $this->redirectToRoute('mission_list');
    }
}
