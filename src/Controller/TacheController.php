<?php
namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\RecompenseService;

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
    public function create(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, TacheRepository $tacheRepository, RecompenseService $recompenseService): JsonResponse
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
        $tache->setUser($user);
    
        if (!empty($data['dueDate'])) {
            // Assurer que la dueDate est bien en format 'Y-m-d'
            $dueDate = \DateTime::createFromFormat('Y-m-d', $data['dueDate'],new \DateTimeZone('America/Toronto'));
            if ($dueDate === false) {
                return $this->json(['error' => 'Date invalide.'], 400);
            }
            $tache->setDueDate($dueDate);
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
    public function update(Request $request, Tache $tache, EntityManagerInterface $em, ValidatorInterface $validator, TacheRepository $tacheRepository, RecompenseService $recompenseService): JsonResponse {
        if ($tache->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès interdit à cette tâche.'], 403);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['titre'])) {
            $tache->setTitre($data['titre']);
        }

        if (array_key_exists('description', $data)) {
            $tache->setDescription($data['description']);
        }

        if (isset($data['tag'])) {
            $tache->setTag($data['tag']);
        }

        if (isset($data['priority'])) {
            $tache->setPriority($data['priority']);
        }

        $wasAlreadyCompleted = $tache->isCompleted(); // avant modif
        $nowCompleted = $data['completed'] ?? $wasAlreadyCompleted;

        if (isset($data['completed'])) {
            $tache->setCompleted($data['completed']);

            if ($data['completed']) {
                $tache->setCompletedAt(new \DateTime('now', new \DateTimeZone('America/Toronto')));
            } else {
                $tache->setCompletedAt(null);
            }
        }

        if (isset($data['pinned'])) {
            $tache->setPinned($data['pinned']);
        }

        if (array_key_exists('dueDate', $data)) {
            $dueDate = \DateTime::createFromFormat('Y-m-d', $data['dueDate'], new \DateTimeZone('America/Toronto'));
            if (!$dueDate) {
                return $this->json(['error' => 'Date invalide. Format attendu : Y-m-d'], 400);
            }
            $tache->setDueDate($dueDate);
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

        // 🔥 Vérification des récompenses seulement si la tâche vient d’être complétée
        if (!$wasAlreadyCompleted && $nowCompleted) {
            $user = $this->getUser();
            $nbTachesCompletees = $tacheRepository->count([
                'user' => $user,
                'completed' => true
            ]);

            $recompenseService->verifierEtDebloquerRecompenses($user, [
                'tachesCompletees' => $nbTachesCompletees
            ]);
        }

        return $this->json($tache, 200, [], ['groups' => 'tache:read']);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(string $id, TacheRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $tache = $repo->find($id);
    
        if (!$tache) {
            return $this->json(['error' => 'Tâche introuvable.'], 404);
        }
    
        if ($tache->getUser() !== $this->getUser()) {
            return $this->json(['error' => 'Accès interdit à cette tâche.'], 403);
        }
    
        $em->remove($tache);
        $em->flush();
    
        return $this->json(null, 204);
    }
}    