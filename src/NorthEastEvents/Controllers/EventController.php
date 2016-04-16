<?php

namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Models\Base\EventQuery;
use NorthEastEvents\Models\UserQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use NorthEastEvents\Bootstrap;
use Slim\App;

class EventController extends Controller {
    // TODO: Do all the work- all the event controller functions!
    // TODO: Limit list functions to only returning a certain amount of recent additions.

    /**
     * Front-end Controllers
     */
    
    public function GetEvents(Request $req, Response $res, array $args){
        $this->pagetitle = "All Events";
        $events = EventQuery::create()->find();

        return $this->render($res, "/events/events.html.twig", [
            "events" => $events
        ]);
    }

    public function CreateEventGet(Request $req, Response $res, array $args){
        $this->pagetitle = "Creating New Event";
        
        return $this->render($res, "/events/create.html.twig");
    }

    public function CreateEventPost(Request $req, Response $res, array $args){

    }

    public function EventOperations(Request $req, Response $res, array $args){
        $this->pagetitle = "Event " . $args["eventID"];

        $event = EventQuery::create()->findOneById($args["eventID"]);
        if($event == null){
            return $this->NotFound(null, $req, $res, $args);
        }

        return $this->render($res, "/events/event.html.twig", [
            "event" => $event
        ]);
    }

    public function GetEventUsers(Request $req, Response $res, array $args){
        $event = EventQuery::create()->findOneById($args["eventID"]);
        if($event == null){
            return $this->NotFound(null, $req, $res, $args);
        }

        return $this->render($res, "/events/users.html.twig", [
            "event" => $event,
            "users" => $event->getUsers()
        ]);
    }

    public function RegisterEvent(Request $req, Response $res, array $args){
        // TODO: do all the things
//        return $res->withHeader("Location", $req->getAttribute("route"));
    }

    public function DeregisterEvent(Request $req, Response $res, array $args){

    }

    /**
     * API Controllers
     */

    public function APIGetEvents(Request $req, Response $res, array $args){

    }

    public function APICreateEvent(Request $req, Response $res, array $args){

    }

    public function APIEventOperations(Request $req, Response $res, array $args){

    }

    public function APIGetEventThreads(Request $req, Response $res, array $args){

    }

    public function APIRegisterThreads(Request $req, Response $res, array $args){

    }

    public function APIDeregisterThreads(Request $req, Response $res, array $args){

    }
}