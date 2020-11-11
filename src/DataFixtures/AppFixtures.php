<?php

namespace App\DataFixtures;

use App\Entity\User;
use Faker\Factory as faker;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = faker::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setEmail($faker->email);
            $user->setPassword($this->encoder->encodePassword($user, "password"));
            $manager->persist($user);
        }
        $admin = new User();
        $admin->setFirstName("greg");
        $admin->setLastName("thorel");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setEmail("gregory.thorel@live.fr");
        $admin->setPassword($this->encoder->encodePassword($user, "admin"));
        $manager->persist($admin);
        $manager->flush();
    }
}
