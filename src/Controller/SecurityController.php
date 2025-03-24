<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class SecurityController extends AbstractController
{
    #[Route('/api/connexion', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        throw new \Exception('Cette méthode ne devrait pas être appelée directement.');
    }

    #[Route('/api/inscription', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserRepository $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em,
        JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'] ?? null;
        $username = $data['username'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$username || !$password) {
            return new JsonResponse(['message' => 'Champs requis manquants'], 400);
        }

        if ($userRepository->findOneBy(['email' => $email])) {
            return new JsonResponse(['message' => 'Email déjà utilisé'], 409);
        }

        if ($userRepository->findOneBy(['username' => $username])) {
            return new JsonResponse(['message' => 'Nom d\'utilisateur déjà pris'], 409);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setRoles(['ROLE_USER']);
        $hashedPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        $em->persist($user);
        $em->flush();

        // Générer le token
        $token = $jwtManager->create($user);

        return new JsonResponse([
            'message' => 'Utilisateur créé avec succès',
            'token' => $token,
            'role' => in_array('ROLE_ADMIN', $user->getRoles()) ? 'admin' : 'user',
            'user' => [
                'email' => $user->getEmail(),
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ]
        ], 201);
    }
}
