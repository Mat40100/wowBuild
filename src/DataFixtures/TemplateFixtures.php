<?php

namespace App\DataFixtures;

use App\Entity\Template;
use App\Entity\WowClass;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TemplateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $Classes = $manager->getRepository(WowClass::class)->findAll();

        foreach ($Classes as $value) {
            $template = new Template();
            $template->setClass($value);
            $manager->persist($template);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            WowClassFixtures::class
        );
    }
}
