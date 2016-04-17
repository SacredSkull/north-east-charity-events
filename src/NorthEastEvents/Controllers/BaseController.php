<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Bootstrap;
use NorthEastEvents\Models\EventQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BaseController extends Controller {
    public function Base(Request $request, Response $response) {
        return $this->render($request, $response, "home.html.twig", [
            "events" => EventQuery::create()->orderByTicketsRemaining(Criteria::ASC)->limit(9)
        ]);
    }
    
    public function Contact(Request $request, Response $response){
        return $this->render($request, $response, "contact.html.twig");
    }
}