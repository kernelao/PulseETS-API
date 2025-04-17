<?php

namespace App\Controller;

use App\Entity\Aide;
use App\Repository\AideRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/aide')]
class AideController extends AbstractController
{
    #[Route('', name: 'aide_admin_liste', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function listeTousMessages(AideRepository $repo): JsonResponse
    {
        return $this->json($repo->findAll(), 200, [], ['groups' => 'aide:read']);
    }

    #[Route('/utilisateur', name: 'aide_user_liste', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function listeMesMessages(Security $security, AideRepository $repo): JsonResponse
    {
        $user = $security->getUser();
        return $this->json($repo->findBy(['user' => $user]), 200, [], ['groups' => 'aide:read']);
    }

    #[Route('', name: 'aide_creer', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function creerMessage(Request $request, EntityManagerInterface $em, Security $security): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $security->getUser();

        $aide = new Aide();
        $aide->setObjet($data['objet'] ?? '');
        $aide->setType($data['type'] ?? '');
        $aide->setContenu($data['contenu'] ?? '');
        $aide->setPriorite($data['priorite'] ?? 3);
        $aide->setDate(new \DateTimeImmutable());
        $aide->setStatut('nouveau');
        $aide->setUser($user);

        $em->persist($aide);
        $em->flush();

        return $this->json(['message' => 'Message créé avec succès'], 201);
    }

    #[Route('/{id}/statut', name: 'aide_modifier_statut', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function modifierStatut(int $id, Request $request, AideRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $aide = $repo->find($id);

        if (!$aide) {
            return $this->json(['message' => 'Message non trouvé'], 404);
        }

        $aide->setStatut($data['statut'] ?? 'en_cours');
        $em->flush();

        return $this->json(['message' => 'Statut mis à jour']);
    }

    #[Route('/{id}/reponse', name: 'aide_repondre_admin', methods: ['PUT'])]
    #[IsGranted('ROLE_ADMIN')]
    public function repondre(int $id, Request $request, AideRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $aide = $repo->find($id);

        if (!$aide) {
            return $this->json(['message' => 'Message non trouvé'], 404);
        }

        $aide->setReponse($data['reponse'] ?? null);
        $aide->setStatut($data['statut'] ?? $aide->getStatut());

        $em->flush();

        return $this->json(['message' => 'Réponse enregistrée']);
    }
}
