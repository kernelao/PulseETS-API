<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/taches', name: 'api_taches_')]
class TacheController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(TacheRepository $tacheRepository): JsonResponse
    {
        $user = $this->getUser();
        $taches = $tacheRepository->findBy(['user' => $user]);

        return $this->json($taches, 200, [], ['groups' => 'tache:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        $tache = new Tache();
        $tache->setTitre($data['titre'] ?? '');
        $tache->setDescription($data['description'] ?? null);
        $tache->setTag($data['tag'] ?? null);
        $tache->setPriority($data['priority'] ?? null);
        $tache->setCompleted($data['completed'] ?? false);
        $tache->setPinned($data['pinned'] ?? false);
        $tache->setUser($user); // 👈 Lier la tâche à l'utilisateur connecté

        if (!empty($data['dueDate'])) {
            $tache->setDueDate(new \DateTime($data['dueDate']));
        }

        $errors = $validator->validate($tache);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->persist($tache);
        $em->flush();

        return $this->json($tache, 201, [], ['groups' => 'tache:read']);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Tache $tache): JsonResponse
    {
        if ($tache->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès interdit à cette tâche.'], 403);
        }

        return $this->json($tache, 200, [], ['groups' => 'tache:read']);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Tache $tache, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        if ($tache->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès interdit à cette tâche.'], 403);
        }

        $data = json_decode($request->getContent(), true);

        $tache->setTitre($data['titre'] ?? $tache->getTitre());
        $tache->setDescription($data['description'] ?? $tache->getDescription());
        $tache->setTag($data['tag'] ?? $tache->getTag());
        $tache->setPriority($data['priority'] ?? $tache->getPriority());
        $tache->setCompleted($data['completed'] ?? $tache->isCompleted());
        $tache->setPinned($data['pinned'] ?? $tache->isPinned());

        if (!empty($data['dueDate'])) {
            $tache->setDueDate(new \DateTime($data['dueDate']));
        }

        $errors = $validator->validate($tache);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->flush();

        return $this->json($tache, 200, [], ['groups' => 'tache:read']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Tache $tache, EntityManagerInterface $em): JsonResponse
    {
        if ($tache->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès interdit à cette tâche.'], 403);
        }

        $em->remove($tache);
        $em->flush();

        return $this->json(null, 204);
    }
}
