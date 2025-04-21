<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\RecompenseService;

#[Route('/api/notes', name: 'api_notes_')]
class NoteController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(NoteRepository $noteRepository): JsonResponse
    {
        $user = $this->getUser();

        $notes = $noteRepository->findBy(['utilisateur' => $user]);

        return $this->json($notes, 200, [], ['groups' => 'note:read']);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, NoteRepository $noteRepository, RecompenseService $recompenseService): JsonResponse
    {
        $user = $this->getUser();
    
        if (!$user) {
            return $this->json(['error' => 'User is null. Are you authenticated?'], 401);
        }
    
        $data = json_decode($request->getContent(), true);
    
        $note = new Note();
        $note->setTitre($data['titre'] ?? '');
        $note->setContenu($data['contenu'] ?? '');
        $note->setCategorie($data['categorie'] ?? null);
        $note->setUtilisateur($user);
    
        $errors = $validator->validate($note);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }
    
        $em->persist($note);
        $em->flush();

        // Comptage des notes actuelles de l'utilisateur
        $nbNotes = $noteRepository->count(['utilisateur' => $user]);

        // Déclenche la vérification des récompenses
        $recompenseService->verifierEtDebloquerRecompenses($user, [
            'notesAjoutees' => $nbNotes
        ]);

    
        return $this->json($note, 201, [], ['groups' => 'note:read']);
    }
    

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(Request $request, Note $note, EntityManagerInterface $em, ValidatorInterface $validator): JsonResponse
    {
        if ($note->getUtilisateur() !== $this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }

        $data = json_decode($request->getContent(), true);

        $note->setTitre($data['titre'] ?? $note->getTitre());
        $note->setContenu($data['contenu'] ?? $note->getContenu());
        $note->setCategorie($data['categorie'] ?? $note->getCategorie());

        $errors = $validator->validate($note);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        $em->flush();

        return $this->json($note, 200, [], ['groups' => 'note:read']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Note $note, EntityManagerInterface $em): JsonResponse
    {
        if ($note->getUtilisateur() !== $this->getUser()) {
            return $this->json(['error' => 'Unauthorized'], 403);
        }

        $em->remove($note);
        $em->flush();

        return $this->json(null, 204);
    }
}
