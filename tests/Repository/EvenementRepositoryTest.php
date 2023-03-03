<?php
namespace App\Tests\Repository;

use App\Entity\Evenement;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EvenementRepositoryTest extends KernelTestCase
{
    private $evenementRepo;
    private $event;

    public function setUp() : void
    {
        // (1) boot the Symfony kernel
        self::bootKernel();

        // (2) use static::getContainer() to access the service container and the repository
        $this->evenementRepo = static::getContainer()->get(EvenementRepository::class);
        $this->event = new Evenement();
        
    }
    
    public function testFindByType() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        $event->setType(1);
        
        $events = $this->evenementRepo->findByType(1);

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
        $this->assertLessThanOrEqual(new \DateTime(), $events[0]->getCreatedAt());
        $this->assertSame($event->getType(),$events[0]->getType());
    }
    
    public function testFindToCome() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        $event->setDate(new \DateTime());
        
        $events = $this->evenementRepo->findToCome();
        $this->assertNotNull($events);
        $this->assertInstanceOf('DateTime', $events[0]->getCreatedAt());
        $this->assertLessThanOrEqual($events[0]->getDate(), $event->getDate());
    }

    public function testFindByTypeToCome() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        $event->setDate(new \DateTime());
        $event->setType(0);

        $events = $this->evenementRepo->findByTypeToCome(0);
        if ($events) {
            $this->assertLessThanOrEqual($events[0]->getDate(), $event->getDate());
            $this->assertSame($event->getType(),$events[0]->getType());
        }
        
    }

    public function testFindHasHappened() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        $event->setDate(new \DateTime());
        
        $events = $this->evenementRepo->findHasHappened();
        $this->assertNotNull($events);
        $this->assertInstanceOf('DateTime', $events[0]->getCreatedAt());
        $this->assertLessThanOrEqual(new \DateTime(), $events[0]->getCreatedAt());
        $this->assertLessThanOrEqual($event->getDate(), $events[0]->getDate());
        $this->assertLessThanOrEqual(count($events), 2);
        $this->assertLessThanOrEqual($events[0]->getDate(), $events[1]->getDate());
    }

    public function testFindHasHappenedAndToCome() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        
        $events = $this->evenementRepo->findHasHappenedAndToCome();
        $this->assertNotNull($events);
        $this->assertLessThanOrEqual(12, count($events));
        $this->assertLessThanOrEqual(count($events), 2);
       
        $this->assertLessThanOrEqual($events[0]->getUpdatedAt(), $events[1]->getUpdatedAt());
    }

    public function testFindAllHasHappenedAndToCome() 
    {        
        // (3) run some service & test the result
        $event = $this->event;
        
        $events = $this->evenementRepo->findAllHasHappenedAndToCome();
        $this->assertNotNull($events);
        $this->assertLessThanOrEqual(count($events), 2);
        $this->assertLessThanOrEqual($events[0]->getUpdatedAt(), $events[1]->getUpdatedAt());
    }

}