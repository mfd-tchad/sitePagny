<?php
// tests/Controller/MairieControllerTest.php
namespace App\Tests\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MairieControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;

    public function setUp() : void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testMairiePageIsUp()
    {
        $this->client->request('GET', '/mairie');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('mairie'));
        $this->assertResponseIsSuccessful();
    }

    public function testDemarchesPageIsUp()
    {
        $this->client->request('GET', '/demarches');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('demarches'));
        $this->assertResponseIsSuccessful();
    }

    public function testUrbanismePageIsUp()
    {
        $this->client->request('GET', '/urbanisme');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('urbanisme'));
        $this->assertResponseIsSuccessful();
    }

    public function testConseilMunicipalPageIsUp()
    {
        $this->client->request('GET', '/conseilmunicipal');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('conseilmunicipal'));
        $this->assertResponseIsSuccessful();
    }

    public function testFinancesPageIsUp()
    {
        $this->client->request('GET', '/finances');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('finances'));
        $this->assertResponseIsSuccessful();
    }





}