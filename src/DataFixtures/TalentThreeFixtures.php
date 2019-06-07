<?php

namespace App\DataFixtures;

use App\Entity\TalentThree;
use App\Entity\WowClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TalentThreeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array= [
            "Guerrier" => ["Armes", "Fureur", "Protection"],
            "Mage" => ["Givre", "Feu", "Arcanes"],
            "Druide" => ["Restauration", "Féral", "Equilibre"],
            "Démoniste" => ["Affliction", "Démonologie", "Destruction"],
            "Voleur" => ["Combat", "Finesse", "Assassinat"],
            "Chaman" => ["Elementaire", "Amélioration", "Restauration"],
            "Chasseur" => ["Précision", "Maitrise des bêtes", "Survie"],
            "Paladin" => ["Vindicte", "Protection", "Sacré"],
            "Prêtre" => ["Sacré", "Discipline", "Ombre"]
            ];
        $classes = $manager->getRepository(WowClass::class)->findAll();

        foreach($array as $className => $threes){
            foreach($classes as $class){
                if($class->getName() === $className) {
                    foreach ($threes as $three){
                        $t = new TalentThree();
                        $t->setName($three);
                        $t->setTemplate($class->getTemplate());
                        $manager->persist($t);
                    }
                }
            }
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            WowClassFixtures::class,
            TemplateFixtures::class
        );
    }
}
