<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Feature;
use App\Entity\Product;
use Liior\Faker\Prices;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Bezhanov\Faker\ProviderCollectionHelper;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    private $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher)
    {
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        // use the factory to create a Faker\Generator instance
        $faker = Factory::create();
        $faker->addProvider(new Prices($faker));
        ProviderCollectionHelper::addAllProvidersTo($faker);
        // ----------création des Users
        for ($u = 0; $u < 10; $u++) {
            $user = new User();
            $user->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'));
            $manager->persist($user);

            //--------Création des Customers
            for ($c = 0; $c < 10; $c++) {
                $customer = new Customer();
                $customer->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->email())
                    ->setCompany($faker->company())
                    ->setUser($user);
                $manager->persist($customer);
                for ($b = 0; $b < 10; $b++) {
                    $brand = new Brand();
                    $brand->setName($faker->sentence(1));
                    $manager->persist($brand);

                    for ($p = 0; $p < 10; $p++) {
                        $product = new Product();
                        $product->setName($faker->productName)
                            ->setSlug(strtolower($this->slugger->slug($product->getName())))
                            ->setDescription($faker->paragraph())
                            ->setPrice($faker->price($min = 4000, $max = 20000))
                            ->setBrand($brand);
                        $manager->persist($product);

                        for ($f = 0; $f < 10; $f++) {
                            $feature = new Feature();
                            $feature->setMemory($faker->randomElement([32, 64, 128, 256]))
                                ->setColor($faker->colorName())
                                ->setBluetooth($faker->randomElement(['YES', 'NO']))
                                ->setCamera($faker->randomElement(['YES', 'NO']))
                                ->setWifi($faker->randomElement(['YES', 'NO']))
                                ->setVideo4k($faker->randomElement(['YES', 'NO']))
                                ->setProduct($product);
                            $manager->persist($feature);
                        }
                    }
                }
                $manager->flush();
            }
        }
    }
}
