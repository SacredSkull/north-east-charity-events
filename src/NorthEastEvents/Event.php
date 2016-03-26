<?php

namespace NorthEastEvents;

use NorthEastEvents\Base\Event as BaseEvent;

/**
 * Skeleton subclass for representing a row from the 'event' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Event extends BaseEvent
{
    // Singleton ciconia instance
    public function preSave($con = null) {
        $this->setBodyHTML(Bootstrap::getCiconia()->render($this->getBody()));
        return true;
    }
}
