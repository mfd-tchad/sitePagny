<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;
    /**
     * @var UserPasswordHasherInterface
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('mairie');
        $user->setFirstname('Mairie');
        $user->setLastName("Pagny La Blanche Côte");
        $user->setPassword($this->encoder->hashPassword($user, 'Pagny'));
        $manager->persist($user);

        $manager->flush();

        $user = new User();
        $user->setUsername('mfd');
        $user->setLastname("Dewulf");
        $user->setFirstname("Marie-Françoise");
        $user->setPassword($this->encoder->hashPassword($user, 'mfd5345'));
        $manager->persist($user);

        $manager->flush();
    }
}
