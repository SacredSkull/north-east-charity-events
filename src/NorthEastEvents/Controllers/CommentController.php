<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Bootstrap;
use NorthEastEvents\Models\Base\EventQuery;
use NorthEastEvents\Models\Comment;
use NorthEastEvents\Models\CommentQuery;
use NorthEastEvents\Models\ThreadQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class CommentController extends Controller {
    public function CreateComment(Request $req, Response $res, $args){
        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        if($this->current_user == null){
            $this->Unauthorised("You must login to create comments.", $req, $res, $args);
        }


        $body = $req->getParsedBody()['body'] ?? null;
        $event = EventQuery::create()->findOneById($args["eventID"] ?? null);
        $thread = ThreadQuery::create()->findOneById($args["threadID"] ?? null);

        if($event == null){
            $flash->addMessage("Error", "Commenting failed|You cannot comment on an event that does not exist.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        }

        if($thread == null){
            $flash->addMessage("Error", "Commenting failed|You cannot comment on a thread that does not exist.");
            return $res->withHeader("Location", $router->pathFor("EventOperations", ["eventID" => $event->getId()]));
        }

        if($thread->getEventID() != $event->getId()){
            $flash->addMessage("Error", "Commenting failed|No valid thread could be found for this event.");
            return $res->withHeader("Location", $router->pathFor("EventOperations", ["eventID" => $event->getId()]));
        }

        $comment = new Comment();
        $comment->setBody($body)->setUser($this->current_user);

        $thread->addComment($comment);
        $thread->save();

        return $res->withHeader("Location", $router->pathFor("EventThreadOperations", ["eventID" => $event->getId(), "threadID" => $thread->getId()]));
    }

    public function DeleteComment(Request $req, Response $res, $args){
        $flash = $this->ci->get("flash");
        $router = $this->ci->get("router");

        if($this->current_user == null){
            return $this->Unauthorised($req, $res, $args);
        }

        $comment = CommentQuery::create()->findOneById($args["commentID"]);
        if($comment == null){
            return $this->NotFound($req, $res, $args);
        }

        if($this->current_user->isAdmin() || $comment->getUserID() == $this->current_user->getId()){
            $comment->delete();
            $flash->addMessage("Success", "The comment was deleted.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        } else {
            $flash->addMessage("Error", "You do not have permissions to do this.");
            return $res->withHeader("Location", $router->pathFor("Home"));
        }
    }
}