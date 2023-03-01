<?php
namespace App\Tests\Repository;
use Date;
use App\Entity\Evenement;

// use Doctrine\Persistence\ManagerRegistry ;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class EvenementRepositoryTest extends KernelTestCase
{
    
    public function testPastEvents() 
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container
        $container = static::getContainer();

        // (3) run some service & test the result
        $event = new Evenement();
        $event->setType(1);
        $event->setTitre('Test');
        $event->setDate(new \DateTime());
        
        $evenementRepo = $container->get(EvenementRepository::class);
        $events = $evenementRepo->findByType(1);

        /*
        $evenementRepo = $this->createMock('EvenementRepository::class');
        $evenementRepo->expects(self::once())
            ->method('findHasHappened')
            ->willReturn([
                $event,
            ])
        ;
        
        $container->set(EvenementRepository::class, $evenementRepo);
        */
        
        $this->assertNotNull($events);
        $this->assertInstanceOf('DateTime', $events[0]->getCreatedAt());
        $this->assertLessThanOrEqual($event->getCreatedAt(), $events[0]->getCreatedAt());
        $this->assertSame($event->getType(),$events[0]->getType());
    }
    

}