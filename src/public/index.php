<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// bootstrap application
require_once __DIR__ . "/../config/bootstrap.php";

$app = \NorthEastEvents\Bootstrap::getSlim();

// load routes
require_once __DIR__ . "/../routes/Controller.php";
require_once __DIR__ . "/../routes/BaseController.php";
require_once __DIR__ . "/../routes/EventsController.php";
require_once __DIR__ . "/../routes/TestController.php";
require_once __DIR__ . "/../routes/ThreadController.php";
require_once __DIR__ . "/../routes/UserController.php";

$app->run();