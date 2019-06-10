<?php


namespace App\DataFixtures;

use App\Service\Slugify;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\Article;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class ArticleFixtures extends Fixture implements DependentFixtureInterface
{

    private $slug;

    /**
     * ArticleFixtures constructor.
     * @param Slugify $slugify
     */
    public function __construct(Slugify $slugify)
    {
        $this->slug = $slugify;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $article = new Article();
            $article->setTitle(mb_strtolower($faker->word() . ' ' . $faker->word()));
            $article->setContent(mb_strtolower($faker->realText(1000)));
            $article->setCategory($this->getReference('categorie_' . $faker->biasedNumberBetween($min = 0, $max = 4)));
            $article->addTag($this->getReference('tag_' . $faker->biasedNumberBetween($min = 0, $max = 4)));
            $article->setSlug($this->slug->generate($article->getTitle()));
            $article->setAuthor($this->getReference('author_user'));
            $manager->persist($article);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}