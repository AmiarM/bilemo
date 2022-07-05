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
        // ----------création des customers
        for ($c = 0; $c < 5; $c++) {
            $customer = new Customer();
            $customer->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setEmail($faker->email())
                ->setCompany($faker->company())
                ->setPassword($this->hasher->hashPassword($customer, 'password'));
            $manager->persist($customer);

            //--------Création des users
            for ($u = 0; $u < 5; $u++) {
                $user = new User();
                $user->setFirstName($faker->firstName())
                    ->setLastName($faker->lastName())
                    ->setEmail($faker->email())
                    ->setCustomer($customer);
                $manager->persist($user);
                for ($b = 0; $b < 5; $b++) {
                    $brand = new Brand();
                    $brand->setName($faker->sentence(1));
                    $manager->persist($brand);

                    for ($p = 0; $p < 5; $p++) {
                        $product = new Product();
                        $product->setName($faker->productName)
                            ->setDescription($faker->paragraph())
                            ->setPrice($faker->price($min = 4000, $max = 20000))
                            ->setBrand($brand);
                        $manager->persist($product);

                        for ($f = 0; $f < 5; $f++) {
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
