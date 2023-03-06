<?php
namespace App\Tests\Entity;

use App\Entity\Contact;
use PHPUnit\Framework\TestCase;


class ContactTest extends TestCase
{
    /**
     * Default new Contact test
     *
     * @return void
     */
    public function testNew()
    {
        $contact = new Contact();
        $this->assertNull($contact->getEmail());
        $this->assertNull($contact->getNom());
        $this->assertNull($contact->getPrenom());
        $this->assertNull($contact->getTel());
        $this->assertNull($contact->getSujet());
    }

    public function testSetGet()
    {
        $contact = new Contact();
        $email = "tata@tutu.fr";
        $contact->setEmail($email);
        $this->assertSame($email, $contact->getEmail());
        
        $nom = "Dupont";
        $contact->setNom($nom);
        $this->assertSame($nom, $contact->getNom());

        $sujet = 'Test sujet';
        $contact->setSujet($sujet);
        $this->assertSame($sujet, $contact->getSujet());
    }
}