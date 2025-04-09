<?php

namespace App\Service;

use App\Entity\Recompense;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class RecompenseService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Vérifie et débloque les récompenses pour un utilisateur.
     *
     * @param User $user
     * @param array $progres Ex : ['notesAjoutees' => 5, 'tachesCompletees' => 10, 'sessionsCompletees' => 3]
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

        $nouvelles = [];

        foreach ($badges as $badge) {
            $type = $badge['type'];
            $seuils = $badge['valeurs'];
            $progression = $progres[$type] ?? 0;

            foreach ($seuils as $seuil) {
                $cle = $type . '-' . $seuil;
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

        if (count($nouvelles) > 0) {
            $this->em->flush();
        }

        return $nouvelles;
    }
}
