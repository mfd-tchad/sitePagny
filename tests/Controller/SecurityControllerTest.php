<?php
// tests/Controller/SecurityControllerTest.php
namespace App\Tests\Controller;

use Symfony\Component\BrowserKit\Request;
use Symfony\Component\BrowserKit\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;

    public function setUp() : void
    {
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
}