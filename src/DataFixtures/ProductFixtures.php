<?php

namespace App\DataFixtures;

use App\Entity\Collection;
use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $slugify = new Slugify();
        $faker = Factory::create('fr_FR');
        $repository = $manager->getRepository(Collection::class);
        $collections = $repository->findAll();

        for ($i = 0; $i < 50; $i++) {

            $name = $faker->word;
            $product = new Product;
            $product->setName(ucwords($name));
            $product->setSlug($slugify->slugify($name));
            $product->setPictureUrl($faker->imageUrl(720, 960, 'cats'));
            $product->setDateAdd(new \DateTime());
            $product->setSku('PRODUCT#' . $i);
            $product->setPrice(rand(10, 200));
            $product->setCollection($collections[rand(0, (count($collections) - 1))]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
