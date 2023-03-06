<?php
// tests/Controller/HomeControllerTest.php
namespace App\Tests\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;

    public function setUp() : void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testHomepageIsUp()
    {
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('home'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h1', 'Bienvenue');
    }

    public function testViePratiquePageIsUp()
    {
        $this->client->request('GET', '/viepratique');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('viepratique'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Vie pratique");
    }

    public function testScolairePageIsUp()
    {
        $this->client->request('GET', '/scolaire');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('scolaire'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"scolaire");
    }

    public function testServiceAinesPageIsUp()
    {
        $this->client->request('GET', '/serviceaines');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('serviceaines'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Service");
    }

    public function testOrduresPageIsUp()
    {
        $this->client->request('GET', '/ordures');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('ordures'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Gestion des");
    }

    public function testAnimationEtLoisirsPageIsUp()
    {
        $this->client->request('GET', '/animationetloisirs');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('animationetloisirs'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Animation");
    }

    public function testJeunessePageIsUp()
    {
        $this->client->request('GET', '/jeunesse');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('jeunesse'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Jeunesse");
    }

    public function testAinesPageIsUp()
    {
        $this->client->request('GET', '/aines');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('aines'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Ainés");
    }

    public function testVideGreniersPageIsUp()
    {
        $this->client->request('GET', '/videgreniers');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('videgreniers'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Greniers");
    }

    public function testConcertsPageIsUp()
    {
        $this->client->request('GET', '/concerts');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('concerts'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Concerts");
    }

    public function testFetesPageIsUp()
    {
        $this->client->request('GET', '/fetes');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('fetes'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Fêtes");
    }

    public function testSportsEtLoisirssPageIsUp()
    {
        $this->client->request('GET', '/sportsetloisirs');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('sportsetloisirs'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Sport");
    }

    public function testAssociationsPageIsUp()
    {
        $this->client->request('GET', '/associations');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('associations'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Associations");
    }

    public function testIleAuxEnfantsPageIsUp()
    {
        $this->client->request('GET', '/ileauxenfants');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('ileauxenfants'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Enfants");
    }

    public function testHistoriquePageIsUp()
    {
        $this->client->request('GET', '/historique');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('historique'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Historique");
    }
    
    public function testSaintGregoirePageIsUp()
    {
        $this->client->request('GET', '/saintgregoire');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('saintgregoire'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Grégoire");
    }

    public function testEglisePageIsUp()
    {
        $this->client->request('GET', '/eglise');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('eglise'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Eglise");
    }

    public function testReservePageIsUp()
    {
        $this->client->request('GET', '/reserve');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('reserve'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Réserve");
    }

    public function testPatrimoinePageIsUp()
    {
        $this->client->request('GET', '/patrimoine');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('patrimoine'));
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('h2',"Patrimoine");
    }

}
