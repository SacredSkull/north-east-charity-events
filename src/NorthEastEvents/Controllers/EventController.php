<?php

namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Models\Base\EventQuery;
use NorthEastEvents\Models\EventUsers;
use NorthEastEvents\Models\ThreadQuery;
use NorthEastEvents\Models\UserQuery;
use NorthEastEvents\Models\WaitingList;
use NorthEastEvents\Models\WaitingListQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use NorthEastEvents\Bootstrap;
use Slim\App;

class EventController extends Controller {
    const EVENTS_PER_PAGE = 12;

    public $resource_type = "Event";
    public $not_allowed_message = "You do not have permission to modify this Event.";
    public $not_found_message = "An Event could not be found with the information provided.";
    public $generic_error = "An error occurred attempting to interact with this Event.";

    // TODO: Do all the work- all the event controller functions!
    // TODO: Limit list functions to only returning a certain amount of recent additions.

    /**
     * Front-end Controllers
     */
    
    public function GetEvents(Request $req, Response $res, array $args){
        $this->pagetitle = "All Events";
        $page = $args["page"] ?? 1;
        $events = EventQuery::create()->paginate($page, self::EVENTS_PER_PAGE);

        if($page > $events->getLastPage()) {
            return $res->withHeader("Location", "/events/".$events->getLastPage());
        }

        return $this->render($req, $res, "/events/events.html.twig", [
            "events" => $events,
        ]);
    }

    public function CreateEventGet(Request $req, Response $res, array $args){
        $this->pagetitle = "Creating New Event";
        
        return $this->render($req, $res, "/events/create.html.twig");
    }

    public function CreateEventPost(Request $req, Response $res, array $args){
        $username = $req->getParsedBody()['username'] ?? null;
        $password = $req->getParsedBody()['password'] ?? null;
        $email = $req->getParsedBody()['email'] ?? null;
        $fname = $req->getParsedBody()['first_name'] ?? null;
        $lname = $req->getParsedBody()['last_name'] ?? null;
        $avatar =$req->getParsedBody()['avatar'] ?? null;

        $previousDetails = [
            "Username" => $username,
            "Password" => null,
            "Email" => $email,
            "FirstName" => $fname,
            "LastName" => $lname,
            "Avatar" => $avatar
        ];
    }

    public function EventOperations(Request $req, Response $res, array $args){
        $this->pagetitle = "Event " . $args["eventID"] ?? null;

        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            return $this->NotFound(null, $req, $res, $args);
        }

        return $this->render($req, $res, "/events/event.html.twig", [
            "event" => $event
        ]);
    }

    public function EventThreadOperations(Request $req, Response $res, array $args){
        $this->pagetitle = "Event " . $args["eventID"] ?? null;

        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            return $this->NotFound("A thread could not be found with the provided information.", $req, $res, $args);
        }

        $thread = ThreadQuery::create()->findOneById($args["threadID"] ?? null);
        if($thread == null) {
            return $this->NotFound("Could not find any thread by this ID.", $req, $res, $args);
        }

        return $this->render($req, $res, "/events/thread.html.twig", [
            "event" => $event,
            "thread" => $thread
        ]);
    }

    public function GetEventUsers(Request $req, Response $res, array $args){
        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            return $this->NotFound(null, $req, $res, $args);
        }

        return $this->render($req, $res, "/events/users.html.twig", [
            "event" => $event,
            "users" => $event->getUsers()
        ]);
    }

    public function RegisterEvent(Request $req, Response $res, array $args){
        if($this->current_user == null)
            return $this->Unauthorised("You must login or register before you can acquire a place.", $req, $res, $args);
        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            return $this->NotFound("Could not find an Event with the requested information.", $req, $res, $args);
        }
        
        $body = sprintf("<h1>North East Charity Music Events</h1><p>Hey.<br/>You're now signed up to %s. Congratulations! If you can't make it to the event, please <a href='%s'>revoke your ticket with this link</a> (or on the event page) to allow others to take your place.<br/><br/>Thanks,<br/>The NE Charity Music Events Team</p>",
            $this->ci->get("router")->pathFor("EventDeregister", [
                'eventID' => $event->getId()
            ])
        );

        if(!$event->hasTickets()){
            // Add to waiting list and send email.
            $body = sprintf("<h1>North East Charity Music Events</h1><p>Hey.<br/>Sorry we couldn't get you signed up for %s. We've added you to the waiting list though, so check your email for updates.<br/><br/>Thanks,<br/>The NE Charity Music Events Team</p>",
                $event->getTitle());

            $this->sendEmail($this->current_user->getEmail(), "Half way there...", $body);
            $waiting = new WaitingList();
            $waiting->setUser($this->current_user);
            $waiting->setEvent($event);
            $this->ci->get("flash")->addMessage("Warning", "No tickets for this event left! You've been put on the waiting list, and will be emailed with further updates.");
            return $res->withHeader("Location", $this->ci->get("router")->pathFor("EventOperations", [
                'eventID' => $event->getId()
            ]))->withStatus(302);
        }

        // The event has tickets
        $event->addUser($this->current_user);
        mail($this->current_user->getEmail(), "See you there!", $body);

        $this->ci->get("flash")->addMessage("Success", sprintf("You've been registered for %s! Please make sure to unregister if you can't make it, for whatever reason.", $event->getTitle()));
        return $res->withHeader("Location", $this->ci->get("router")->pathFor("EventOperations", [
            'eventID' => $event->getId()
        ]))->withStatus(302);
    }

    public function DeregisterEvent(Request $req, Response $res, array $args){
        if($this->current_user == null)
            return $this->Unauthorised($req, $res, $args);
        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            return $this->NotFound("Could not find an Event with the requested information.", $req, $res, $args);
        }

        if(!$event->getUsers()->contains($this->current_user)){
            $this->ci->get("flash")->addMessage("Error", "You haven't signed up for this event yet!");
            return $res->withHeader("Location", $this->ci->get("router")->pathFor("EventOperations", [
                'eventID' => $event->getId()
            ]))->withStatus(302);
        }
        
        $event->removeUser($this->current_user);

        $firstWaiting = WaitingListQuery::create()->orderByCreatedAt()->findOne();

        if($firstWaiting != null) {
            $event->addUser($firstWaiting->getUser());
            $firstWaiting->delete();
            // Add email saying that they are off the waiting list
            $this->sendEmail($firstWaiting->getUser()->getEmail(), "Woo! You got in!", sprintf("Congratulations! A space has been freed and now you've got yourself a ticket for %s. 
            If you've already made other plans, or if something else comes up before the event, please <a href='%s'>revoke your ticket.</a>", $event->getTitle(),
                $this->ci->get("router")->pathFor("EventDeregister", [
                'eventID' => $event->getId()
                ])
            ));
        }
        $event->save();
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