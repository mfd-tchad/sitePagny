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

    public function testContactMessageTelNOK()
    {
        $crawler = $this->client->request('GET', '/contact');
        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact[nom]'] = "Dupont";
        $form['contact[prenom]'] = "Marcel";
        $form['contact[tel]'] = "03.29.85.54.21"; // tel format not accepted
        $form['contact[email]'] = "mdupont@test.fr";
        $form['contact[sujet]'] = "Test";
        $form['contact[message]'] = "Bonjour, c'est juste un test. Ne pas en tenir compte";

        $this->client->submit($form);
        $this->assertSelectorTextContains('div.alert.alert-danger', "Vous avez fait une erreur dans le remplissage");
    }

    public function testContactMessageEmailNOK()
    {
        $crawler = $this->client->request('GET', '/contact');
        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact[nom]'] = "Dupont";
        $form['contact[prenom]'] = "Marcel";
        $form['contact[tel]'] = "03.29.85.54.21"; 
        $form['contact[email]'] = "mdupont@test"; // not an email address
        $form['contact[sujet]'] = "Test";
        $form['contact[message]'] = "Bonjour, c'est juste un test. Ne pas en tenir compte";

        $this->client->submit($form);
        
        $this->assertSelectorTextContains('div.alert.alert-danger', "Vous avez fait une erreur dans le remplissage");
    }

    public function testContactMessageTooShort()
    {
        $crawler = $this->client->request('GET', '/contact');
        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact[nom]'] = "Dupont";
        $form['contact[prenom]'] = "Marcel";
        $form['contact[tel]'] = "03.29.85.54.21"; 
        $form['contact[email]'] = "mdupont@test";
        $form['contact[sujet]'] = "Test";
        $form['contact[message]'] = "Bonjour,"; // Too short

        $this->client->submit($form);
        $this->assertSelectorTextContains('div.alert.alert-danger', "Vous avez fait une erreur dans le remplissage");
    }

    public function testSendContactMessageOK()
    {
        $crawler = $this->client->request('GET', '/contact');
        $form = $crawler->selectButton('Envoyer')->form();
        $form['contact[nom]'] = "Dupont";
        $form['contact[prenom]'] = "Marcel";
        $form['contact[tel]'] = "0329855421";
        $form['contact[email]'] = "mdupont@test.fr";
        $form['contact[sujet]'] = "Test";
        $form['contact[message]'] = "Bonjour, c'est juste un test. Ne pas en tenir compte";

        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', "Votre email a bien été envoyé");

    }
}