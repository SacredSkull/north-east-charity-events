<?php

namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Models\Base\EventQuery;
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
        
        return $this->ci->get("view")->render($res, "example.twig.html", $this->renderVariables([
            "events" => $events
        ]));
    }

    public function CreateEvent(Request $req, Response $res, array $args){
        $this->pagetitle = "Create Event";
    }

    public function EventOperations(Request $req, Response $res, array $args){
        $this->pagetitle = "Event " . $args["eventID"];
    }

    public function GetUserEvents(Request $req, Response $res, array $args){

    }

    public function RegisterEvent(Request $req, Response $res, array $args){

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