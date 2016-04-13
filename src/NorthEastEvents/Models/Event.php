<?php

namespace NorthEastEvents\Models;

use NorthEastEvents\Models\Base\Event as BaseEvent;
use Propel\Runtime\Connection\ConnectionInterface;
use NorthEastEvents\Bootstrap;

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
    public function preSave(ConnectionInterface $con = null) {
        $this->setBodyHTML(Bootstrap::getCI()->get("ciconia")->render($this->getBody()));
        return true;
    }
}
