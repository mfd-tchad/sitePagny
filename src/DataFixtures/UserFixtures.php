<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{
    private $encoder;
    /**
     * @var UserPasswordEncoderInterface
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('mairie');
        $user->setFirstname('Mairie');
        $user->setLastName("Pagny La Blanche Côte");
        $user->setPassword($this->encoder->encodePassword($user, 'Pagny'));
        $manager->persist($user);

        $manager->flush();

        $user = new User();
        $user->setUsername('mfd');
        $user->setLastname("Dewulf");
        $user->setFirstname("Marie-Françoise");
        $user->setPassword($this->encoder->encodePassword($user, 'mfd5345'));
        $manager->persist($user);

        $manager->flush();
    }
}
