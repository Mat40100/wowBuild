<?php

namespace App\DataFixtures;

use App\Entity\TalentPoint;
use App\Entity\TalentThree;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TalentPointFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $array = [
            ["Armes", "Frappe héroïque améliorée", "Réduit le coût en rage de votre technique Frappe héroïque de X points.", 3, 1],
            ["Armes", "Deflection", "Augmente de X% vos chances de parer", 5, 1],
            ["Armes", "Pourfendre amélioré", "Augmente de X% les points de dégâts infigés par la technique pourfendre.", 3, 1],
            ["Armes", "Charge améliorée", "Augmente la quantité de Rage générée par votre technique Charge de X", 2, 2],
            ["Armes", "Maîtrise tactique", "Vous conservez jusqu'à X de vos points de rage lorsque vous changez de posture.", 5, 2],
            ["Armes", "Coup de tonnerre amélioré", "Réduit le coût de votre technique Coup de tonnerre de X points de rage", 3, 2],
            ["Armes", "Fulgurance améliorée", "Augmente de X% vos chances d'infliger un coup critique avec la technique Fulgurance", 2, 3],
            ["Armes", "Gestion de la rage", "Augmente de X% le temps nécessaire au processus de déperdition des points de rage lorsque vous cessez le combat.", 1, 3],
            ["Armes", "Blessures profondes", "Vos coups critiques font saigner l'adversare et lui infligent X% des points de dégâts moyens de votre arme en 12sec.", 3, 3],
            ["Armes", "Spécialisation Armes à deux mains", "Augmente de X% les points de dégaâts que vous infligez avec les armes à deux mains.", 5, 4],
            ["Armes", "Empâler", "Augmente de X% es points de dégâts infligés par vos coups critiques, lorsque vous utilisez vos techniques en postures de combat, défensive et berserker", 2, 4],
            ["Armes", "Spécialisation Hache", "Augmente de X% vos chances d'infliger un coup critique avec les haches.", 5, 5],
            ["Armes", "Sweeping Strikes", "Vos 5 prochaines attaques de mêlée touchent un adversaire proche supplémentaire.", 1, 5],
            ["Armes", "Spécialisation Masse", "Vous confère X% de chances d'étourdir votre cible pendant 3 sec avec une Masse.", 5, 5],
            ["Armes", "Spécialisation Epée", "Vous confère X% de chances de bénéficier d'une attaque supplémentaire sur la même cible, après avoir infligé des dégâts avec votre épée.", 5, 5],
            ["Armes", "Spécialisation Arme d'hast", "Augmente de X% vos chances d'infliger un coup critique aves les armes d'hast", 5 , 6],
            ["Armes", "Brise-genou amélioré", "Confère à votre technique brise-genou X% de cances d'immobiliser votre cible pendant 5 sec", 3, 6],
            ["Armes", "Frappe Mortelle", "Une attaque vicieuse qui inflige les points de dégâts de l'arme plus 85 et blesse la cible. L'effet des sorts de soins dont elle est la cible est réduit de 50% pendant 10 sec.", 1, 7],
        ];

        foreach($array as $point) {
            $three = $manager->getRepository(TalentThree::class)->createQueryBuilder('a')
                ->where('a.name = :name')
                ->setParameters(['name' => $point[0]])
                ->getQuery()
                ->getResult()
            ;

            $p = new TalentPoint();
            $p->setName($point[1]);
            $p->setDescription($point[2]);
            $p->setPoints($point[3]);
            $p->setThreeLvl($point[4]);
            $p->setThree($three[0]);

            $manager->persist($p);
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
