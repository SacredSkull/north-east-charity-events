<?php

namespace NorthEastEvents\Controllers;

use NorthEastEvents\Models\EventQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BaseController extends Controller {
    public function Base(Request $request, Response $response) {
        return $this->ci->get("view")->render($response, "home.html.twig", $this->renderVariables([
            "events" => EventQuery::create()->find()
        ]));
    }
}