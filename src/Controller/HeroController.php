<?php

namespace App\Controller;

use App\Entity\Hero;
use App\Repository\HeroRepository;
use App\Repository\PowerRepository;
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
    private EntityManagerInterface $em;

    public function __construct(HeroRepository $heroRepository, EntityManagerInterface $em, PowerRepository $powerRepository)
    {
        $this->powerRepository = $powerRepository;
        $this->heroRepository = $heroRepository;
        $this->em = $em;
    }

    // Route pour voir tous les héros
    #[Route('/', name: 'list')]
    public function searchAll(): Response
    {
        $heros = $this->heroRepository->findAll();

        return $this->render('hero/index.html.twig', [
            'controller_name' => 'HeroController',
            'heros' => $heros,
        ]);
    }

    // Route pour voir un héro
    #[Route('/{id}/show', name: 'show', methods: ['GET'])]
    public function searchById(Hero $hero): Response
    {
        return $this->render('hero/show.html.twig', [
            'hero' => $hero,
        ]);
    }


    // Route pour créer un héros
    // Route pour créer un héros
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $hero = new Hero();
        $powers = $this->powerRepository->findAll(); // Récupère tous les pouvoirs disponibles

        if ($request->isMethod('POST')) {
            $data = $request->request;

            // Utilisation directe du PowerRepository
            $power = $this->powerRepository->find($data->get('power')); // Récupère le pouvoir sélectionné

            if (!$power) {
                $this->addFlash('error', 'Pouvoir sélectionné invalide.');
                return $this->redirectToRoute('hero_new');
            }

            $hero->setName($data->get('name'))
                ->setImage($data->get('image'))
                ->setSecretIdendity($data->get('secretIdendity'))
                ->setAge((int) $data->get('age'))
                ->setNotableMission($data->get('notableMission'))
                ->setSuccesRate((int) $data->get('succesRate'))
            ->setFailRate((int) $data->get('failRate'))
                ->setPowerLink($power);

            $this->em->persist($hero);
            $this->em->flush();

            $this->addFlash('success', 'Héros créé avec succès !');
            return $this->redirectToRoute('hero_list');
        }

        return $this->render('hero/new.html.twig', [
            'hero' => $hero,
            'powers' => $powers, // Passe les pouvoirs au template
        ]);
    }


    // Route pour modifier un héro
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hero $hero): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request;

            $hero->setName($data->get('name'))
                ->setImage($data->get('image')) // Sous forme de lien temporairement || Rajout d'upload prévue 
                ->setSecretIdendity($data->get('secretIdendity'))
                ->setAge((int) $data->get('age'))
                ->setNotableMission($data->get('notableMission'))
                ->setSuccesRate((int) $data->get('succesRate'))
                ->setFailRate((int) $data->get('failRate'));

            $this->em->flush();

            return $this->redirectToRoute('hero_list');
        }

        return $this->render('hero/edit.html.twig', ['hero' => $hero]);
    }
    // Route pour supprimer un héro
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Hero $hero): Response
    {
        // Vérification du token CSRF pour une suppression sécurisée (Sera utile lors de l'ajout du système d'authentification)
        if (!$this->isCsrfTokenValid('delete' . $hero->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide. Suppression impossible.');
            return $this->redirectToRoute('hero_list');
        }

        // Suppression de l'entité
        $this->em->remove($hero);
        $this->em->flush();

        // Message flash pour confirmer la suppression
        $this->addFlash('success', 'Héros supprimé avec succès.');

        return $this->redirectToRoute('hero_list');
    }
}
