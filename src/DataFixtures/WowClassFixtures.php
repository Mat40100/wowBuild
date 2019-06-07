<?php

namespace App\DataFixtures;

use App\Entity\WowClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class WowClassFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $array = [
            ["Mage", "mage.png", "", ""],
            ["Guerrier", "war.png", "", ""],
            ["Démoniste", "demo.png", "", ""],
            ["Chaman", "shaman.png", "", ""],
            ["Prêtre", "priest.png", "", ""],
            ["Voleur", "rogue.png", "", ""],
            ["Paladin", "paladin.png", "", ""],
            ["Chasseur", "hunter.png", "", ""],
            ["Druide", "druid.png", "", ""]
        ];

        foreach($array as $item){
            $n = new WowClass();
            $n->setName($item[0]);
            $n->setImg($item[1]);
            $n->setColor($item[2]);
            $n->setFontColor($item[3]);

            $manager->persist($n);
        }
        $manager->flush();
    }
}
