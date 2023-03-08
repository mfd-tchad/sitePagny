<?php
// tests/Controller/SecurityControllerTest.php
namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;
    private $userRepository;

    public function setUp() : void
    {
        // (1) create client
        $this->client = static::createClient();

        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testLoginPageIsUp()
    {
        $this->client->request('GET', '/login');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('login'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('legend','Login');
    }

    public function testLoginFail()
    {
        $crawler = $this->client->request('GET', '/login');

        // Get the form
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => 'anything'
        ]);
        $this->client->submit($form);
        $this->assertResponseRedirects('http://localhost/login');
        $crawler = $this->client->followRedirect();

        // check failure is noticed
        $this->assertSelectorExists('.alert.alert-danger');

        // check there is no link named Administration
        $this->AssertEmpty($crawler->selectLink('Administration'));

        // check below that there is a link named Evénements
        $this->AssertEmpty($crawler->selectLink('Evénements'));
    }

    public function testLoginUserSuccess()
    {
        $crawler = $this->client->request('GET', '/login');

        // Get the form
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'user',
            '_password' => 'user5345'
        ]);
        $this->client->submit($form);

        // check we are on the home page
        $this->assertResponseRedirects('http://localhost/');
        $this->client->followRedirect();

        // check below that there is no link named Administration since it's a simple user
        $this->AssertEmpty($crawler->selectLink('Administration'));

        // check below that there is no link named Evénements either
        $this->AssertEmpty($crawler->selectLink('Evénements'));
    }

    public function testLoginAdminSuccess()
    {
        $crawler = $this->client->request('GET', '/login');

        // Get the form
        $form = $crawler->selectButton('Se connecter')->form([
            '_username' => 'adminuser',
            '_password' => 'admin5345'
        ]);
        $this->client->submit($form);

        // check we are on the home page
        $this->assertResponseRedirects('http://localhost/');
        $crawler = $this->client->followRedirect();

        // check below that there is a link named Administration since it's an admin
        $liAdmin = $crawler->selectLink('Administration');
        $this->AssertNotEmpty($liAdmin);

        // check below that there is a link named Evénements
        $linkCrawler = $crawler->selectLink('Evénements');
        $this->AssertNotEmpty($linkCrawler);
    }

}