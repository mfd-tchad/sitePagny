<?php
// tests/Controller/EvenementsControllerTest.php
namespace App\Tests\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\UserRepository;
use App\Repository\EvenementRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminEvenementControllerTest extends WebTestCase
{
    private $client = null;
    private $userRepository;
    private $testUser;
    private $testAdminUser;
    private $urlGenerator;

    public function setUp() : void
    {
        // (1) create client
        $this->client = static::createClient();

        // (2) use static::getContainer() to access the service container and the repository
        $this->userRepository = static::getContainer()->get(UserRepository::class);
        
        // (3) retrieve the test users
        $this->testUser = $this->userRepository->findOneByUsername('user');
        $this->testAdminUser = $this->userRepository->findOneByUsername('adminuser');

        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
   
    public function testAdminPageIsDownWhileUserLoggedIn()
    {
        // simulate $testUser being logged in
        $this->client->loginUser($this->testUser);
        
        // Check $testUser has ADMIN role
        $this->assertNotContains('ROLE_ADMIN',$this->testUser->GetRoles());

        // check Admin page is not reachable
        $this->client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $this->client->request('GET', $this->urlGenerator->generate('admin.evenement.index'));
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminPageIsUpWhileAdminLoggedIn()
    {
        // simulate $testUser being logged in
        $this->client->loginUser($this->testAdminUser);
        
        // Check $testUser has ADMIN role
        $this->assertContains('ROLE_ADMIN',$this->testAdminUser->GetRoles());

        // check Admin page is reachable
        $this->client->request('GET', '/admin');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Administration des événements');

        $this->client->request('GET', $this->urlGenerator->generate('admin.evenement.index'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Administration des événements');
    }

    public function testAdminPageIsReachableFromHomePageWhileAdminLoggedIn()
    {
        // simulate $testUser being logged in
        $this->client->loginUser($this->testAdminUser);
        
        // Check Admin page is reachable via the menu from the home page
        $crawler = $this->client->request('GET', '/');
       
        // check below that there is a link named Administration
        $liAdmin = $crawler->selectLink('Administration');
        $this->AssertNotEmpty($liAdmin);

        // check below that there is a link named Evénements
        $linkCrawler = $crawler->selectLink('Evénements');
        $this->AssertNotEmpty($linkCrawler);
        
        // ...then, get the Link object and click :
        $link = $linkCrawler->link();
        $crawler = $this->client->click($link);
        
        // check we are on the Event Admin page
        $this->assertSelectorTextContains('h2', 'Administration des événements');

    }

    public function testNewEventPageIsUpWhileAdminLoggedIn()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);

        // check New Event page is reachable
        $this->client->request('GET', '/admin/evenement/new');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('admin.evenement.new'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2', 'Création d\'un événement');

    }

    public function testNewEventPageIsReachableFromAdminPage()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);

        // Get Events Admin page
        $crawler = $this->client->request('GET', '/admin');

        // check below that there is a link named Créer un nouvel Evénement
        $linkCrawler = $crawler->selectLink('Créer un nouvel Evenement');
        $this->AssertNotEmpty($linkCrawler);
        
        // ...then, get the Link object and click :
        $link = $linkCrawler->link();
        $crawler = $this->client->click($link);
        
        // check we are on the Event Admin page
        $this->assertSelectorTextContains('h2', 'Création d\'un événement');

    }

    public function testAddNewEvent()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);
        
        // Get to the creation page
        $crawler = $this->client->request('GET', '/admin/evenement/new');

        // Get the form
        $form = $crawler->selectButton('Créer')->form();
        $form['evenement[type]'] = 0;
        $form['evenement[titre]'] = 'Test';
        $date = new \Datetime();
        $form['evenement[date]'] = $date->format('Y-m-d');
        $form['evenement[description]'] = '<p> Bonjour, ceci est un test de création de nouvel événement <p>';

        $this->client->submit($form);
        $this->assertResponseRedirects('/admin');
        $this->client->followRedirect();
        $this->assertSelectorExists('div.alert.alert-success');

    }
 
    public function testEditEventPageIsReachableFromAdminPage()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);

        // Get Events Admin Page
        $crawler = $this->client->request('GET', '/admin');

        // check below that there is a link named Editer
        $linkCrawler = $crawler->selectLink('Editer');
        $this->AssertNotEmpty($linkCrawler);
        
        // ...then, get the Link object and click :
        $link = $linkCrawler->link();
        $crawler = $this->client->click($link);
        
        // check we are on the Edit Event page
        $this->assertSelectorTextContains('h2', 'Edition d\'un événement');

    }

    public function testEditEvent()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);
        
        // Get Events Admin Page
        $crawler = $this->client->request('GET', '/admin');

        // get the Edit event link object and click :
        $link = $crawler->selectLink('Editer')->link();
        $crawler = $this->client->click($link);

        // Get to the edit page directly
        // $crawler = $this->client->request('GET', '/admin/evenement/');

        // Get the form and modify titre
        $form = $crawler->selectButton('Enregistrer')->form([
            'evenement[titre]' => 'Test',
        ]);
        $form['evenement[type]']->select('7');
        
        $this->client->submit($form);
        $this->assertResponseRedirects('/admin');
        $this->client->followRedirect();
        $this->assertSelectorExists('div.alert.alert-success');
    }
   
    public function testDeleteEvent()
    {
        // simulate $testAdminUser being logged in
        $this->client->loginUser($this->testAdminUser);
        
        // Get Events Admin Page
        $crawler = $this->client->request('GET', '/admin');

        // get the Edit event link object and click :
        $form = $crawler->selectButton('Supprimer')->form();
        $this->assertNotNull($form);
        $this->client->submit($form);
        $this->assertResponseRedirects('/admin');
        $crawler = $this->client->followRedirect();
        $this->assertSelectorExists('div.alert.alert-success');
    }
}