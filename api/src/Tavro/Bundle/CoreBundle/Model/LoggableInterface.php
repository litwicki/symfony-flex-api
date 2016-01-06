<?php

namespace Tavro\Bundle\CoreBundle\Model;

use Tavro\Bundle\CoreBundle\Entity\User;

/**
 * Used for outputting to activity log
 */
Interface LoggableInterface
{

    /**
     * Adds a log message when member creates an item
     *
     * @return String (log entry)
     */
    public function logCreate(User $user, $entity);

    /**
     * Adds a log message when member edits or marks inactive an item
     *
     * @return String (log entry)
     */
    public function logEdit(User $user, $entity, $args);

    /**
     * Adds a log message when member deletes an item
     *
     * @return String (log entry)
     */
    public function logDelete(User $user, $entity);

}