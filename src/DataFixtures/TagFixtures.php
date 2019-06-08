<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Faker;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    const TAGS = [
        'Dev',
        'Veille',
        'Hardware',
        'Big Data',
        'Cloud'
    ];

    public function load(ObjectManager $manager)
    {

        $faker  =  Faker\Factory::create('fr_FR');

        foreach (self::TAGS as $key => $tagName) {
            $tag = new Tag();
            $tag->setName($tagName);
            $manager->persist($tag);
            $this->addReference('tag_' . $key, $tag);
        }
        $manager->flush();
    }
}
