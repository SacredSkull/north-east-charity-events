<?php

namespace NorthEastEvents\Models;

use NorthEastEvents\Models\Base\Event as BaseEvent;
use Propel\Runtime\Connection\ConnectionInterface;
use NorthEastEvents\Bootstrap;
use Symfony\Component\Validator\Constraints\DateTime;

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

    public function hasTickets(){
        return $this->getTicketsRemaining() >= 1;
    }

    public function getTicketsTaken(){
        return EventQuery::create()->findOneById($this->getId())->getUsers()->count();
    }

    public function hasFinished(){
        if(new DateTime() > $this->getDate()){
            return true;
        }
        return false;
    }

    public function computeTicketsRemaining(ConnectionInterface $con) {
        $stmt = $con->prepare('SELECT ((SELECT tickets FROM event e2 WHERE e2.id = e1.id) - (SELECT COUNT(*) FROM event_users eu2 WHERE eu2.eventID = e1.id)) as remaining FROM event e1 WHERE e1.id = :p1;');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}
