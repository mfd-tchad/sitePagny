<?php
// tests/Controller/EvenementsControllerTest.php
namespace App\Tests\Controller;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EvenementsControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;

    public function setUp() : void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testActualitePageIsUp()
    {
        $this->client->request('GET', '/actualite');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('actualite'));
        $this->assertResponseIsSuccessful();
    }

    public function testEvenementsPageIsUp()
    {
        $this->client->request('GET', '/evenements');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('evenements'));
        $this->assertResponseIsSuccessful();
    }

    public function testAlaUnePageIsUp()
    {
        $this->client->request('GET', '/alaune');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('alaune'));
        $this->assertResponseIsSuccessful();
    }

    public function testFlashInfosPageIsUp()
    {
        $this->client->request('GET', '/flashinfos');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('flashinfos'));
        $this->assertResponseIsSuccessful();
    }

    public function testActuConseilMunicipalPageIsUp()
    {
        $this->client->request('GET', '/actuconseilmunicipal');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('actuconseilmunicipal'));
        $this->assertResponseIsSuccessful();
    }

    public function testDecesPageIsUp()
    {
        $this->client->request('GET', '/deces');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('deces'));
        $this->assertResponseIsSuccessful();
    }

    public function testMariagesPageIsUp()
    {
        $this->client->request('GET', '/mariages');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('mariages'));
        $this->assertResponseIsSuccessful();
    }

    public function testActuFetesPageIsUp()
    {
        $this->client->request('GET', '/actufetes');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('actufetes'));
        $this->assertResponseIsSuccessful();
    }

    public function testShowEventPageIsUp()
    {
       /* 
        $eventRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')
            ->getRepository('Evenement::class');
        */
        $this->client->request('GET', '/evenements/conseil-municipal-compte-rendu-cm-18');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', '/evenements/flash-info-calendrier-de-collecte-des-ordures-menageres-24');
        $this->assertResponseIsSuccessful();

    }
}