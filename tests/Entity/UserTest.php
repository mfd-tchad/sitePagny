<?php
namespace App\Tests\Entity;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    /**
     * Default new user test
     *
     * @return void
     */
    public function testNew()
    {
        $user = new User();
        $roles = $user->getRoles();
        $this->assertSame('ROLE_USER', $roles[0]);
    }

    public function testSetGet()
    {
        $user = new User();
        $username = 'Tintin';
        $user->setUsername($username);
        $this->assertSame($username, $user->getUsername());
    }

}