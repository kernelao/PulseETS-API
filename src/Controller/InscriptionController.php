<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'inscription', methods: ['POST'])]
    public function inscrire(Request $request): JsonResponse
    {
        // Récupérer les données de la requête
        $data = json_decode($request->getContent(), true);

        if (!$data || !isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse(['error' => 'Données invalides'], 400);
        }

        // Ici, ajouter la logique pour enregistrer l'utilisateur (ex: via un service ou un repository)

        return new JsonResponse(['message' => 'Inscription réussie'], 201);
    }
}
