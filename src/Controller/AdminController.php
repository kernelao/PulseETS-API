<?php

namespace App\Controller;

use App\Entity\Element;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/admin')]
//#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/elements/{id}/toggle', name: 'admin_toggle_element', methods: ['POST'])]
    public function toggleElement(int $id): JsonResponse
    {
        $element = $this->entityManager->getRepository(Element::class)->find($id);

        if (!$element) {
            return new JsonResponse(['message' => 'Élément introuvable'], 404);
        }

        $element->setActive(!$element->isActive());
        $this->entityManager->flush();

        return new JsonResponse([
            'message' => 'Élément mis à jour',
            'active' => $element->isActive(),
        ]);
    }

    #[Route('/elements', name: 'admin_list_elements', methods: ['GET'])]
    public function listElements(): JsonResponse
    {
        try {
            $elements = $this->entityManager->getRepository(Element::class)->findAll();

            $data = array_map(function (Element $element) {
                return [
                    'id' => $element->getId(),
                    'name' => $element->getName(),
                    'type' => $element->getType(),
                    'active' => $element->isActive(),
                ];
            }, $elements);

            return new JsonResponse($data);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
