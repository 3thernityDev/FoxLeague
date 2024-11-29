<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Repository\HeroRepository;
use App\Repository\PowerRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/hero', name: 'hero_')]
class HeroController extends AbstractController
{
    private HeroRepository $heroRepository;
    private PowerRepository $powerRepository;
    private TeamRepository $teamRepository;
    private EntityManagerInterface $em;

    public function __construct(
        HeroRepository $heroRepository,
        EntityManagerInterface $em,
        PowerRepository $powerRepository,
        TeamRepository $teamRepository
    ) {
        $this->powerRepository = $powerRepository;
        $this->heroRepository = $heroRepository;
        $this->teamRepository = $teamRepository;
        $this->em = $em;
    }

    #[Route('/', name: 'list')]
    public function searchAll(): Response
    {
        // Récupère tous les héros pour les afficher dans la liste
        $heros = $this->heroRepository->findAll();

        return $this->render('hero/index.html.twig', [
            'heros' => $heros,
        ]);
    }

    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function searchById(Hero $hero): Response
    {
        // Affiche les détails d'un héros particulier
        return $this->render('hero/show.html.twig', [
            'hero' => $hero,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $hero = new Hero();
        $powers = $this->powerRepository->findAll();
        $teams = $this->teamRepository->findAll();

        if ($request->isMethod('POST')) {
            // Récupère les données du formulaire
            $data = $request->request;

            $power = $this->powerRepository->find($data->get('power'));
            $team = $this->teamRepository->find($data->get('team')); // Peut être null

            // Validation des taux de succès et d'échec
            $successRate = (int) $data->get('succesRate');
            $failRate = (int) $data->get('failRate');

            if ($successRate < 0 || $successRate > 100 || $failRate < 0 || $failRate > 100) {
                $this->addFlash('error', 'Les taux de succès et d\'échec doivent être compris entre 0 et 100.');
                return $this->render('hero/new.html.twig', [
                    'hero' => $hero,
                    'powers' => $powers,
                    'teams' => $teams,
                ]);
            }

            // Vérification que la somme des taux de succès et d'échec ne dépasse pas 100%
            if ($successRate + $failRate > 100) {
                $this->addFlash('error', 'La somme des taux de succès et d\'échec ne doit pas dépasser 100%.');
                return $this->render('hero/new.html.twig', [
                    'hero' => $hero,
                    'powers' => $powers,
                    'teams' => $teams,
                ]);
            }

            // Si une équipe est sélectionnée, on vérifie qu'elle n'est pas pleine
            if ($team && $team->isFull()) {
                // Vérifie si l'équipe contient déjà 5 héros
                $this->addFlash('error', 'Cette équipe est pleine (maximum 5 héros).');
                return $this->redirectToRoute('hero_new');
            }

            // On attribue les valeurs au héros
            $hero->setName($data->get('name'))
                ->setImage($data->get('image'))
                ->setSecretIdendity($data->get('secretIdendity'))
            ->setAge((int) $data->get('age'))
            ->setSuccesRate($successRate)
                ->setFailRate($failRate)
                ->setPowerLink($power)
            ->setTeam($team); // L'équipe peut être null

            // Enregistre le héros dans la base de données
            $this->em->persist($hero);
            $this->em->flush();

            // Message de succès et redirection vers la liste des héros
            $this->addFlash('success', 'Héros créé avec succès !');
            return $this->redirectToRoute('hero_list');
        }

        // Affiche le formulaire de création de héros avec les pouvoirs et équipes
        return $this->render('hero/new.html.twig', [
            'hero' => $hero,
            'powers' => $powers,
            'teams' => $teams,
        ]);
    }


    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hero $hero): Response
    {
        // Si la méthode est POST, on traite la soumission du formulaire
        if ($request->isMethod('POST')) {
            // Récupère les données du formulaire
            $data = $request->request;

            $power = $this->powerRepository->find($data->get('power'));
            $team = $this->teamRepository->find($data->get('team')); // Peut être null

            // Validation des taux de succès et d'échec
            $successRate = (int) $data->get('succesRate');
            $failRate = (int) $data->get('failRate');

            if ($successRate < 0 || $successRate > 100 || $failRate < 0 || $failRate > 100) {
                $this->addFlash('error', 'Les taux de succès et d\'échec doivent être compris entre 0 et 100.');
                return $this->render('hero/edit.html.twig', [
                    'hero' => $hero,
                    'powers' => $this->powerRepository->findAll(),
                    'teams' => $this->teamRepository->findAll(),
                ]);
            }

            // Vérification que la somme des taux de succès et d'échec ne dépasse pas 100%
            if ($successRate + $failRate > 100) {
                $this->addFlash('error', 'La somme des taux de succès et d\'échec ne doit pas dépasser 100%.');
                return $this->render('hero/edit.html.twig', [
                    'hero' => $hero,
                    'powers' => $this->powerRepository->findAll(),
                    'teams' => $this->teamRepository->findAll(),
                ]);
            }

            // Si une équipe est sélectionnée et différente de l'équipe actuelle, on vérifie qu'elle n'est pas pleine
            if ($team && $team !== $hero->getTeam() && $team->isFull()) {
                // Vérifie si l'équipe contient déjà 5 héros
                $this->addFlash('error', 'Cette équipe est pleine (maximum 5 héros).');
                return $this->redirectToRoute('hero_edit', ['id' => $hero->getId()]);
            }

            // Mise à jour des valeurs du héros
            $hero->setName($data->get('name'))
                ->setImage($data->get('image'))
                ->setSecretIdendity($data->get('secretIdendity'))
            ->setAge((int) $data->get('age'))
            ->setSuccesRate($successRate)
                ->setFailRate($failRate)
                ->setPowerLink($power)
            ->setTeam($team); // L'équipe peut être null

            // Sauvegarde les changements
            $this->em->flush();

            // Message de succès et redirection vers la liste des héros
            $this->addFlash('success', 'Héros modifié avec succès.');
            return $this->redirectToRoute('hero_list');
        }

        // Affiche le formulaire d'édition de héros avec les pouvoirs et équipes
        return $this->render('hero/edit.html.twig', [
            'hero' => $hero,
            'powers' => $this->powerRepository->findAll(),
            'teams' => $this->teamRepository->findAll(),
        ]);
    }


    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Hero $hero): Response
    {
        // Vérifie le token CSRF pour la suppression
        if (!$this->isCsrfTokenValid('delete' . $hero->getId(), $request->request->get('_token'))) {
            // Message d'erreur si le token CSRF est invalide
            $this->addFlash('error', 'Token CSRF invalide. Suppression impossible.');
            return $this->redirectToRoute('hero_list');
        }

        // Suppression du héros de la base de données
        $this->em->remove($hero);
        $this->em->flush();

        // Message de succès après la suppression
        $this->addFlash('success', 'Héros supprimé avec succès.');
        return $this->redirectToRoute('hero_list');
    }
}
