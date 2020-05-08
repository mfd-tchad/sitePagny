<?php
// src/Listener/ImageCacheSubscriber.php
namespace App\Listener;

use App\Entity\Evenement;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifeCycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct (CacheManager $cacheManager, UploaderHelper $uploaderHelper )
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    // this method can only return the event names; you cannot define a
    // custom method name to execute when each event triggers
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
             'preUpdate',
        ];
    }

    public function preRemove(LifeCycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Evenement) {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));
       // $this->logActivity('remove', $args);
    }

    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Evenement) {
            return;
        }
        if ($entity->getImageFile() instanceof UploadedFile) {
            $this->cacheManager->remove($this->uploaderHelper->asset($entity,'imageFile'));
        }
    }

    private function logActivity(string $action, PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof Evenement) {
            return;
        }

        // ... get the entity information and log it somehow
    }
}
