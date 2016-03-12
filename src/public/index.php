<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require "../config/bootstrap.php";

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
