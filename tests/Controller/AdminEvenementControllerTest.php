<?php
// tests/Controller/EvenementsControllerTest.php
namespace App\Tests\Controller;

use App\Entity\Evenement;
use App\Repository\UserRepository;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminEvenementControllerTest extends WebTestCase
{
    private $client = null;
    private $userRepository;
    private $testUser;
    private $urlGenerator;

    public function setUp() : void
    {
        $this->client = static::createClient();

        // (2) use static::getContainer() to access the service container and the repository
        $this->userRepository = static::getContainer()->get(UserRepository::class);

        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testAdminPageIsUpWhileAdminLoggedIn()
    {

        // retrieve the test user
        $testUser = $this->userRepository->findOneByUsername('mairie');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
        
        // Check $testUser has ADMIN role
        $this->assertContains('ROLE_ADMIN',$testUser->GetRoles());

        // check Admin page is reachable
        $this->client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('admin.evenement.index'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2', 'Administration des événements');
    }

    public function testAdminPageIsDownWhileUserLoggedIn()
    {

        // retrieve the test user
        $testUser = $this->userRepository->findOneByUsername('user');

        // simulate $testUser being logged in
        $this->client->loginUser($testUser);
        
        // Check $testUser has ADMIN role
        $this->assertNotContains('ROLE_ADMIN',$testUser->GetRoles());

        // check Admin page is reachable
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }
}