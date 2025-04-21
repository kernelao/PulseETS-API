<?php

// src/Service/RecompenseService.php
namespace App\Service;

use App\Entity\Recompense;
use App\Entity\User;
use App\Entity\PulsePoint;
use Doctrine\ORM\EntityManagerInterface;

class RecompenseService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Vérifie et débloque les récompenses pour un utilisateur, tout en ajoutant des PulsePoints.
     * Désormais, les PulsePoints sont ajoutés à chaque seuil atteint, même si la récompense est déjà débloquée.
     *
     * @param User $user
     * @param array $progres
     * @return array Liste des récompenses débloquées
     */
    public function verifierEtDebloquerRecompenses(User $user, array $progres): array
    {
        $dejaDebloquees = $user->getRecompenses()->map(fn($r) => $r->getType() . '-' . $r->getValeur())->toArray();

        $badges = [
            ['type' => 'notesAjoutees', 'valeurs' => [1, 5, 15, 30, 100]],
            ['type' => 'sessionsCompletees', 'valeurs' => [1, 5, 10, 25, 50, 100]],
            ['type' => 'tachesCompletees', 'valeurs' => [1, 5, 20, 50, 100]],
        ];

        // barème des points pour chaque seuil
        $pointsParSeuil = [
            1 => 10,
            5 => 50,
            15 => 150,
            20 => 200,
            25 => 250,
            30 => 300,
            50 => 500,
            100 => 1000,
        ];

        $nouvelles = [];

        foreach ($badges as $badge) {
            $type = $badge['type'];
            $seuils = $badge['valeurs'];
            $progression = $progres[$type] ?? 0;

            foreach ($seuils as $seuil) {
                $cle = $type . '-' . $seuil;

                // Toujours ajouter des points si seuil atteint
                if ($progression >= $seuil) {
                    $points = $pointsParSeuil[$seuil] ?? 5;
                    $pulse = new PulsePoint();
                    $pulse->setUtilisateur($user);
                    $pulse->setPoints($points);
                    $pulse->setDateCreated(new \DateTimeImmutable());
                    $this->em->persist($pulse);
                }

                // Débloquer la récompense seulement si pas encore obtenue
                if ($progression >= $seuil && !in_array($cle, $dejaDebloquees)) {
                    $recompense = new Recompense();
                    $recompense->setUtilisateur($user);
                    $recompense->setType($type);
                    $recompense->setNom($type . ' x' . $seuil);
                    $recompense->setValeur($seuil);
                    $recompense->setSeuil($seuil); 
                    $recompense->setDescription('Récompense pour avoir atteint ' . $seuil . ' dans ' . $type);
                    $recompense->setAvatarOffert('');
                    $recompense->setDateDebloquee(new \DateTime());

                    $this->em->persist($recompense);
                    $nouvelles[] = $recompense;
                }
            }            
        }
        
        $types = ['notesAjoutees', 'tachesCompletees', 'sessionsCompletees'];
$tousSeuils = [
    'notesAjoutees' => [1, 5, 15, 30, 100],
    'tachesCompletees' => [1, 5, 20, 50, 100],
    'sessionsCompletees' => [1, 5, 10, 25, 50, 100]
];

$dejaDebloquees = $user->getRecompenses()->map(fn($r) => $r->getType() . '-' . $r->getValeur())->toArray();

$manquantes = false;
foreach ($types as $type) {
    foreach ($tousSeuils[$type] as $seuil) {
        if (!in_array("$type-$seuil", $dejaDebloquees)) {
            $manquantes = true;
            break 2;
        }
    }
}

// Vérifie s'il a déjà reçu le bonus pour tout avoir complété
$bonusDejaDonne = $user->getRecompenses()->exists(fn($k, $r) =>
    $r->getType() === 'bonusComplet'
);

if (!$manquantes && !$bonusDejaDonne) {
    $bonus = new \App\Entity\Recompense();
    $bonus->setUtilisateur($user);
    $bonus->setType('bonusComplet');
    $bonus->setNom('Toutes les récompenses complétées');
    $bonus->setValeur(1000);
    $bonus->setSeuil(0);
    $bonus->setDescription('Félicitations, vous avez débloqué toutes les récompenses possibles !');
    $bonus->setDateDebloquee(new \DateTime());
    $bonus->setAvatarOffert(null);
    $this->em->persist($bonus);

    $points = new \App\Entity\PulsePoint();
    $points->setUtilisateur($user);
    $points->setPoints(1000);
    $points->setDateCreated(new \DateTimeImmutable());
    $this->em->persist($points);

    $nouvelles[] = $bonus;
}
        $this->em->flush();
        return $nouvelles;
    }
}
