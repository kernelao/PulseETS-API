<?php
//installe fixtures
    //coomposer require --dev orm-fixtures
//fichiers servent a réinsérer ces données dans la base
//pour chrger les fixtures
    //php bin/console doctrine:fixtures:load --append
namespace App\DataFixtures;

use App\Entity\Element;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ElementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Avatars
        $manager->persist((new Element())->setName("Jon Doe")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Lina")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Grey Kid")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Incognita")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Julie")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Roussette")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Wild n Serious")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Sequelita")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("80s boy")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Kim Possible")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Cool guy")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Suit man")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("I am Batman")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Suuuuuu")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Elektra")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Tony Stark aka Ironman")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Why so serious?")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Wild n serious on vacation")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Donna")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Neon Lights")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Space Explorer")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Steampunk Voyager")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Viking Warrior")->setType("avatar")->setActive(true));
        $manager->persist((new Element())->setName("Wizard Supreme")->setType("avatar")->setActive(true));

        // Thèmes
        $manager->persist((new Element())->setName("Cyberpunk")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Steampunk")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Space")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Dark Mode")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Neon Lights")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Vintage")->setType("theme")->setActive(true));
        $manager->persist((new Element())->setName("Minimalist")->setType("theme")->setActive(true));

        $manager->flush();
    }
}
