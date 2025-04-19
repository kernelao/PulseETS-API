<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Achat;
use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AuthController extends AbstractController
{
    #[Route('/api/inscription', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword(
            $passwordHasher->hashPassword($user, $data['password'])
        );
        $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();

        $avatar = $em->getRepository(Element::class)->findOneBy([
            'name' => 'defautavatar',
            'type' => 'avatar'
        ]);

        if($avatar) {
            $achat = new Achat();
            $achat->setUtilisateur($user);
            $achat->setElement($avatar);
            $achat->setDateAchat(new \DateTimeImmutable());
            $achat->setIsActive(true);
            
            $user->setAvatarPrincipal($avatar);

            $em->persist($achat);
            $em->persist($user);
            $em->flush();
        }

        return new JsonResponse(['message' => 'Inscription réussie'], 201);
    }
}
