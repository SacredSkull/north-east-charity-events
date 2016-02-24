<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// Constants

define('DEBUG', true);

date_default_timezone_set("Europe/London");

if(!file_exists(__DIR__ . "/vendor")){
    echo "The composer folder is missing! The website cannot run without its dependencies - try running 'vagrant provision', or manually attempt a fix with 'composer install'.";
    exit;
}

require 'vendor/autoload.php';

$settings = require __DIR__ . '/conf/slim.php';
$app = new \Slim\App($settings);


//$app = new \Slim\Slim(array(
//    'view' => new \Slim\Views\Twig(),
//    'templates.path' => './templates',
//    'debug' => DEBUG,
//    'debug.revealHttpVariables' => DEBUG,
//));
//
//$app->view()->parserOptions = array(
//    'cache' => dirname(__FILE__).'/cache',
//    'debug' => DEBUG,
//);

//$app->view()->parserExtensions = array(
//    new \Slim\Views\TwigExtension(),
//    new \Twig_Extensions_Extension_Text(),
//);

// Get container
$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Register component on container
$container['view'] = function ($c) {
    $view = new \Slim\Views\Twig($c->get('settings')['renderer']['template_path'], [
        'cache' => DEBUG ? null : $c->get('settings')['renderer']['cache']
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    return $view;
};

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("Hello");
    $logger = $this->get("logger");
    $logger->addInfo("Logging works like this...");
    return $response;
});

$app->get('/{template}', function(Request $request, Response $response){
    $template = $request->getAttribute("template");

    $example = array(
        array(
            'href' => "/",
            'caption' => 'Example 1'
        ),
        array(
            'href' => "/",
            'caption' => 'Example 2'
        ),
        array(
            'href' => "/",
            'caption' => 'Example 3'
        )
    );

    return $this->get("view")->render($response, "$template.twig.html", [
        // 'name' => $arg['name']]
        'template_name' => $template,
        'navigation' => $example,
        'variable_name' => "Twig variable example"
    ]);
});

$app->run();