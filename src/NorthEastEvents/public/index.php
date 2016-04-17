<?php

use \NorthEastEvents\Controllers;
use \NorthEastEvents\Controllers\Routes;

// bootstrap application
require_once __DIR__ . "/../config/bootstrap.php";

// load Controllers
//require_once __DIR__ . "/../Controllers/Controller.php";
//require_once __DIR__ . "/../Controllers/BaseController.php";
//require_once __DIR__ . "/../Controllers/EventController.php";
//require_once __DIR__ . "/../Controllers/TestController.php";
//require_once __DIR__ . "/../Controllers/ThreadController.php";
//require_once __DIR__ . "/../Controllers/UserController.php";

$bootstrap = new \NorthEastEvents\Bootstrap();
$app = $bootstrap->initialise();
$container = $app->getContainer();

session_start();

if(\NorthEastEvents\Bootstrap::DEBUG)
    $container->get("flash")->addMessage("Info", "Debug mode is on.|Debug mode is still active. Turn it off in the Bootstrap class when ready.");

new Routes\BaseRoutes($container, Controllers\BaseController::class);
new Routes\CommentRoutes($container, Controllers\CommentController::class);
new Routes\EventRoutes($container, Controllers\EventController::class);
new Routes\ThreadRoutes($container, Controllers\ThreadController::class);
new Routes\UserRoutes($container, Controllers\UserController::class);

// These routes should only be used if development is ongoing!
if(\NorthEastEvents\Bootstrap::DEBUG) {
    new Routes\TestRoutes($container, Controllers\TestController::class);
}

$app->run();