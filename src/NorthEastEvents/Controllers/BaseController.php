<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Models\EventQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BaseController extends Controller {
    public function Base(Request $request, Response $response) {
        return $this->render($response, "home.html.twig", [
            "events" => EventQuery::create()->limit(9)->find()
        ]);
    }
}