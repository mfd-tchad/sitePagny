<?php
// tests/Controller/ContactControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private $client = null;
    private $urlGenerator;

    public function setUp() : void
    {
        $this->client = static::createClient();
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }
    
    public function testContactPageIsUp()
    {
        $this->client->request('GET', '/contact');
        $this->assertResponseIsSuccessful();

        $this->client->request('GET', $this->urlGenerator->generate('contact'));
        $this->assertResponseIsSuccessful();
    }
}