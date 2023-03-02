<?php
namespace App\Tests\Entity;
use Date;
use App\Entity\Evenement;
use PHPUnit\Framework\TestCase;


class EvenementTest extends TestCase
{
    /**
     * Default new Evenement test
     *
     * @return void
     */
    public function testNew()
    {
        $evenenement = new Evenement();
        $this->assertNull($evenenement->getImage());
        $this->assertNull($evenenement->getImageFile());
        $this->assertNull($evenenement->getEventPdf());
        $this->assertNull($evenenement->getPdfFile());
    }

    public function testSetGet()
    {
        $evenenement = new Evenement();
        $titre = 'Test titre';
        $evenenement->setTitre($titre);
        $this->assertSame($titre, $evenenement->getTitre());
    }
}