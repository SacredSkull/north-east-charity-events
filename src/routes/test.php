<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/data', function (Request $request, Response $response) {
    for($i = 1; $i < 1000; $i++){
        $user = new \NorthEastEvents\User();
        $user->setUsername("PHIL" . $i);
        $user->save();
    }
    $response->getBody()->write("Hello");
    return $response;
});

$app->get('/example', function(Request $request, Response $response){
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

    return $this->get("view")->render($response, "example.twig.html", [
        // 'name' => $arg['name']]
        'template_name' => "EXAMPLE",
        'navigation' => $example,
        'variable_name' => "Twig variable example"
    ]);
});
