<?php

namespace App\Controller;

use App\Entity\Power;
use App\Repository\PowerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Utilisation de l'annotation correcte
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/power', name: 'power_')]
class PowerController extends AbstractController
{
    private PowerRepository $powerRepository;
    private EntityManagerInterface $em;

    public function __construct(PowerRepository $powerRepository, EntityManagerInterface $em)
    {
        $this->powerRepository = $powerRepository;
        $this->em = $em;
    }

    // Route pour voir tous les pouvoirs
    #[Route('/', name: 'list')]
    public function searchAll(): Response
    {
        $powers = $this->powerRepository->findAll();
        return $this->render('power/index.html.twig', [
            'powers' => $powers
        ]);
    }

    // Route pour créer un pouvoir
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $power = new Power();

        if ($request->isMethod('POST')) {
            $data = $request->request;

            $power->setName($data->get('name'))
                ->setRarity($data->get('rarity'));

            $this->em->persist($power);
            $this->em->flush();

            return $this->redirectToRoute('power_list');
        }

        return $this->render('power/new.html.twig', [
            'power' => $power,
        ]);
    }

    // Route pour modifier un pouvoir
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Power $power): Response
    {
        if ($request->isMethod('POST')) {
            $data = $request->request;

            $power->setName($data->get('name'))
                ->setRarity($data->get('rarity'));

            $this->em->flush();

            return $this->redirectToRoute('power_list');
        }

        return $this->render('power/edit.html.twig', [
            'power' => $power,
        ]);
    }

    // Route pour supprimer un pouvoir
    #[Route('/{id}/delete', name: 'delete', methods: ['POST'])]
    public function delete(Power $power): Response
    {
        // Vérifier si le pouvoir est attribué à des héros
        if (!$power->getHeroLink()->isEmpty()) {
            // Ajout d'un message d'erreur dans la session
            $this->addFlash('error', 'Ce pouvoir est attribué à un ou plusieurs héros. Veuillez d\'abord supprimer ou modifier les héros associés.');
            // Rediriger vers la liste des pouvoirs
            return $this->redirectToRoute('power_list');
        }

        // Si aucune dépendance, suppression du pouvoir
        $this->em->remove($power);
        $this->em->flush();

        // Ajout d'un message de confirmation
        $this->addFlash('success', 'Le pouvoir a été supprimé avec succès.');

        return $this->redirectToRoute('power_list');
    }
}
