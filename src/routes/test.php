<?php

use NorthEastEvents\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$faker = Faker\Factory::create("en_GB");

$app->get('/data[/{wipe}]', function (Request $request, Response $response) use ($faker) {
    if($request->getAttribute('wipe')) {
        \NorthEastEvents\Base\UserQuery::create()->deleteAll();
        \NorthEastEvents\EventQuery::create()->deleteAll();
    }

    $populator = new ORM\Propel2\Populator($faker);
    $populator->addEntity('\NorthEastEvents\User', 100, array(
        'AvatarUrl' => function() use($faker) { return $faker->imageUrl(); },
        'CreatedAt' => null,
        'UpdatedAt' => null,
        'Permission' => function() use($faker) { return $faker->boolean(30)? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF : \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL;},
    ));

    $populator->addEntity('\NorthEastEvents\Event', 50, array(
        'Body' => function() use($faker){ return $faker->realText(1500); },
        'BodyHTML' => null,
        'Tickets' => function() use ($faker) { $faker->numberBetween(20, 100); }
    ));

    $ids = $populator->execute();
    $response->getBody()->write("Hello<br>". print_r($ids));
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
