<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Element;
use App\Entity\PulsePoint;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/user')]
class AchatController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    #[Route('/avatars/buy/{name}', name: 'acheter_avatar_par_nom', methods: ['POST'])]
public function acheterAvatarParNom(string $name): JsonResponse
{
    return $this->traiterAchatParNom($name, 'avatar');
}

#[Route('/themes/buy/{name}', name: 'acheter_theme_par_nom', methods: ['POST'])]
public function acheterThemeParNom(string $name): JsonResponse
{
    return $this->traiterAchatParNom($name, 'theme');
}

private function traiterAchatParNom(string $name, string $type): JsonResponse
{
    $user = $this->getUser();
    if (!$user instanceof User) {
        return new JsonResponse(['message' => 'Utilisateur non connecté'], 403);
    }

    $element = $this->entityManager->getRepository(Element::class)->findOneBy([
        'name' => $name,
        'type' => $type
    ]);

    if (!$element || !$element->isActive()) {
        return new JsonResponse(['message' => ucfirst($type) . ' non disponible'], 404);
    }

    $existing = $this->entityManager->getRepository(Achat::class)->findOneBy([
        'utilisateur' => $user,
        'element' => $element
    ]);

    if ($existing) {
        return new JsonResponse(['message' => ucfirst($type) . ' déjà acheté'], 400);
    }

        $prixParNom = [
            // Avatars
            'Jon Doe' => 0,
            'Lina' => 100,
            'Grey Kid' => 100,
            'Sequelita' => 100,
            'Incognita' => 150,
            'Roussette' => 150,
            'Julie' => 200,
            'Kim Possible' => 200,
            'Cool guy' => 250,
            'Suit man' => 250,
            'Wild n Serious' => 300,
            "80's boy" => 300,
            'I am Batman' => 2500,
            'Suuuuuu' => 2500,
            'Elektra' => 2500,
            'Tony Stark aka Ironman' => 2500,
            'Why so serious?' => 2500,
            'Wild n serious on vacation' => 3000,
            'Donna' => 3000,
            'Neon Lights' => 8000,
            'Space Explorer' => 2000,
            'Steampunk Voyager' => 6000,
            'Viking Warrior' => 5500,
            'Wizard Supreme' => 8000,

            // Thèmes
            'Cyberpunk' => 300,
            'Steampunk' => 500,
            'Space' => 700,
            'Dark Mode' => 200,
            'Neon Lights' => 400,
            'Vintage' => 350,
            'Minimalist' => 250,
        ];

        $prix = $prixParNom[$name] ?? 9999;

    if ($user->getTotalPulsePoints() < $prix) {
        return new JsonResponse(['message' => 'Pas assez de points'], 400);
    }

    $achat = new Achat();
    $achat->setUtilisateur($user);
    $achat->setElement($element);
    $achat->setDateAchat(new \DateTimeImmutable());

    $deduction = new PulsePoint();
    $deduction->setUtilisateur($user);
    $deduction->setPoints(-$prix);
    $deduction->setDateCreated(new \DateTimeImmutable());

    $this->entityManager->persist($achat);
    $this->entityManager->persist($deduction);
    $this->entityManager->flush();

    return new JsonResponse(['message' => ucfirst($type) . ' acheté avec succès']);
}
}
