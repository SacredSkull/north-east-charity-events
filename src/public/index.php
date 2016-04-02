<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// bootstrap application
require __DIR__ . "/../config/bootstrap.php";

$app = \NorthEastEvents\Bootstrap::getSlim();

// load routes
require __DIR__ . "/../routes/Controller.php";
require __DIR__ . "/../routes/BaseController.php";
require __DIR__ . "/../routes/EventsController.php";
require __DIR__ . "/../routes/TestController.php";
require __DIR__ . "/../routes/ThreadController.php";
require __DIR__ . "/../routes/UserController.php";

$app->run();