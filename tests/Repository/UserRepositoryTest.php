<?php
namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    private $userRepo;
    private $user;

    public function setUp() : void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container and the repository
        $this->userRepo = static::getContainer()->get(UserRepository::class);
        $this->user = new User();
        
    }
    
    public function testOneByUsername() 
    {        
        $this->user->setUsername('user');
        $user = $this->userRepo->findOneByUsername('user');
        $this->assertNotNull($user);
        $this->assertSame($this->user->getUsername(), $user->getUsername());
    }

}