<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// bootstrap application
require __DIR__ . "/../config/bootstrap.php";

$app = \NorthEastEvents\Bootstrap::getSlim();

// load routes
require __DIR__ . "/../routes/base.php";
require __DIR__ . "/../routes/users.php";
require __DIR__ . "/../routes/test.php";




$app->run();
