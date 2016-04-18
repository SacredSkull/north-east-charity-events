<?php

namespace NorthEastEvents\Controllers;

use Interop\Container\ContainerInterface;
use NorthEastEvents\Models\Base\EventQuery;
use NorthEastEvents\Models\CharityQuery;
use NorthEastEvents\Models\Event;
use NorthEastEvents\Models\EventRating;
use NorthEastEvents\Models\EventRatingQuery;
use NorthEastEvents\Models\EventUsers;
use NorthEastEvents\Models\EventUsersQuery;
use NorthEastEvents\Models\Thread;
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
        if($this->current_user == null || !$this->current_user->isAdmin()){
            return $this->Unauthorised("This page requires authentication.", $req, $res, $args);
        }

        return $this->render($req, $res, "/events/create.html.twig", [
            "charities" => CharityQuery::create()->find()
        ]);
    }

    public function CreateEventPost(Request $req, Response $res, array $args){
        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        if($this->current_user == null || !$this->current_user->isAdmin()){
            return $this->Unauthorised("This page requires authentication.", $req, $res, $args);
        }

        $title = $req->getParsedBody()['title'] ?? null;
        $charityID = $req->getParsedBody()['charityID'] ?? null;
        $imageUrl = $req->getParsedBody()['imageUrl'] ?? null;
        $date = $req->getParsedBody()['date'] ?? null;
        $location = $req->getParsedBody()['location'] ?? null;
        $body = $req->getParsedBody()['body'] ?? null;
        $tickets = (int)($req->getParsedBody()['tickets'] ?? null);
        $videoUrl = $req->getParsedBody()['videoUrl'] ?? null;

        $date = \DateTime::createFromFormat("H:i d/m/Y", $date);

        $previousDetails = [
            "title" => $title,
            "imageUrl" => $imageUrl,
            "date" => $date,
            "location" => $location,
            "body" => $body,
            "tickets" => $tickets,
            "videoUrl" => $videoUrl,
        ];
        $failure = false;
        if($title == null || strlen($title) < 5){
            $flash->addMessageNow("Error", "Bad title format|Titles should be at least 4 characters long.");
            $failure = true;
        }

        $charity = CharityQuery::create()->findOneById($charityID);
        if($charity == null){
            $flash->addMessageNow("Error", "Charity does not exist|A charity could not be found with the provided information.");
            $failure = true;
        }

        if($date == null || new \DateTime() > $date){
            $flash->addMessageNow("Error", "A valid date must be provided|The date must be in the future. See the default value for an example (HOUR:MIN DD/MM/YYYY)");
            $failure = true;
        }

        if($location == null || strlen($location) < 8){
            $flash->addMessageNow("Error", "Bad location format|Locations should be at least 8 characters long.");
            $failure = true;
        }

        if($body == null || strlen($body) < 10){
            $flash->addMessageNow("Error", "Bad description format|Description should be at least 10 characters long.");
            $failure = true;
        }

        if($tickets == null || $tickets <= 0){
            $flash->addMessageNow("Error", "Bad ticket format|An event needs at least 1 ticket.");
            $failure = true;
        }

        if($failure){
            return $this->render($req, $res, "/events/create.html.twig", [
                "previous_details" => $previousDetails,
                "charities" => CharityQuery::create()->find()
            ]);
        }
        
        $event = new Event();
        $event->setTitle($title)->setBody($body)->setCharity($charity)->setDate($date)->setTickets($tickets)
            ->setImageUrl($imageUrl)->setLocation($location)->save();

        return $res->withHeader("Location", $router->pathFor("CharityOperations", ["charityID" => $charity->getId()]));
    }

    public function CreateThread(Request $req, Response $res, array $args){
        if($this->current_user == null){
            return $this->Unauthorised("You must login to create threads.", $req, $res, $args);
        }

        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        $title = $req->getParsedBody()['title'] ?? null;


        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        if($event == null){
            $flash->addMessage("Error", "Event not found|We were unable to find any event with the submitted information.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        }

        if($title == null || strlen($title) < 4 || strlen($title) > 60){
            $flash->addMessage("Error", "Bad title|Titles can be from 4 to 50 characters long.");
            return $res->withHeader("Location", $router->pathFor("EventOperations", [ "eventID" => $event->getId() ]));
        }

        $thread = new Thread();
        $thread->setUser($this->current_user)->setTitle($title)->setEvent($event)->save();
        return $res->withHeader("Location", $router->pathFor("EventThreadOperations", [ "eventID" => $event->getId(), "threadID" => $thread->getId() ]));
    }

    public function EventRating(Request $req, Response $res, array $args) {
        if($this->current_user == null){
            return $this->Unauthorised("You must login to rate events.", $req, $res, $args);
        }

        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        $rating = (int)($args["rating"] ?? null);

        if($event == null){
            $flash->addMessage("Error", "Event not found!|We were unable to find any event with the submitted information.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        }

        if($rating == null || !is_numeric($rating) || $rating < 1 || $rating > 5){
            $flash->addMessage("Error", "Rating failure|Rating should be performed using the event stars, and must be values between 1 & 5.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        }

        $eventRating = EventRatingQuery::create()
            ->filterByEventID($event->getId())
            ->filterByUserID($this->current_user->getId())
            ->findOneOrCreate();

        $eventRating->setRating($rating);

        return $res->withHeader("Location", $router->pathFor("EventOperations", [ "eventID" => $event->getId() ]));
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

        if($event->hasFinished()){
            $hourdiff = $event->getDate()->diff(new \DateTime())->h;
            $this->ci->get("flash")->addMessage("Error", sprintf("This event has already ended.|Sorry, that event ended $hourdiff hours ago!"));
            return $res->withHeader("Location", $this->ci->get("router")->pathFor("EventOperations", [
                'eventID' => $event->getId()
            ]))->withStatus(302);
        }
        $body = sprintf("<h1>North East Charity Music Events</h1><p>Hey.<br/>You're now signed up to %s. Congratulations! If you can't make it to the event, please <a href='%s'>revoke your ticket with this link</a> (or on the event page) to allow others to take your place.<br/><br/>Thanks,<br/>The NE Charity Music Events Team</p>",
            $event->getTitle(),
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
        $event->save();

        $this->ci->get("flash")->addMessage("Success", sprintf("You've been registered for %s!|Please make sure to unregister if you can't make it, for whatever reason, to give others a chance to attend.", $event->getTitle()));
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