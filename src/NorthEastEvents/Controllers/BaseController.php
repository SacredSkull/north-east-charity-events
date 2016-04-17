<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Bootstrap;
use NorthEastEvents\Models\EventQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BaseController extends Controller {
    public function Base(Request $request, Response $response) {
        if(Bootstrap::DEBUG) {
            $this->ci->get("flash")->addMessage("Success", "This is a test message.|Everything seems great!.");
            $this->ci->get("flash")->addMessage("Warning", "This is a test message.|Everything seems a little off.");
            $this->ci->get("flash")->addMessage("Error", "This is a test message.|Everything has gone totally wrong!!!");
        }

        return $this->render($request, $response, "home.html.twig", [
            "events" => EventQuery::create()->orderByTicketsRemaining(Criteria::ASC)->limit(9)
        ]);
    }
    
    public function Contact(Request $request, Response $response){
        return $this->render($request, $response, "contact.html.twig");
    }
}