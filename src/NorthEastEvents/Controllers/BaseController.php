<?php

namespace NorthEastEvents\Controllers;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class BaseController extends Controller {
    public function Base(Request $request, Response $response) {
        $response->getBody()->write("Hello");
        $logger = $this->ci->get("logger");
        $logger->addInfo("Logging works like this...");
        return $response;
    }
}