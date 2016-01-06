<?php

namespace Tavro\Bundle\CoreBundle\EventListener\Doctrine;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Tavro\Bundle\CoreBundle\Model\EntityInterface;
use Tavro\Bundle\CoreBundle\Model\S3EntityInterface;

class PreUpdateListener
{
    /**
     * Absolutely *NO* logic should be handled here. This is strictly to handle simple
     * Entity updates on dates, statuses, etc.
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if($entity instanceof EntityInterface) {

            /**
             * If "views" or "downloads" is the only change, do not set update_date
             */
            $changes = $args->getEntityChangeSet();
            $keys = array_keys($changes);

            if(!in_array('views', $keys) && !in_array('downloads', $keys)) {

                //@TODO: set the update_date of the Entity.

            }
        }

    }

}