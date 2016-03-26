<?php

use NorthEastEvents\User;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// These routes should only be used if development is ongoing!
if(\NorthEastEvents\Bootstrap::DEBUG) {
    $faker = Faker\Factory::create("en_GB");

    $app->get('/data[/{wipe}]', function (Request $request, Response $response) use ($faker) {
        if ($request->getAttribute('wipe')) {
            $start = microtime(true);
            \NorthEastEvents\Base\UserQuery::create()->deleteAll();
            \NorthEastEvents\EventQuery::create()->deleteAll();
            \NorthEastEvents\EventUsersQuery::create()->deleteAll();
            $response->getBody()->write(sprintf("Wiping users, events, and event bookings (Event Users) took %.3fs<br/>", microtime(true) - $start));
            \NorthEastEvents\Bootstrap::getLogger()->addInfo("[DATA] Wiped users, events and everything else...");
        }

        $start = microtime(true);
        $populator = new NorthEastEvents\ORM\Propel2\Populator($faker);
        $populator->addEntity('\NorthEastEvents\User', 50, array(
            'AvatarUrl' => function () use ($faker) {
                return $faker->imageUrl(180, 180);
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
            'Permission' => function () use ($faker) {
                return $faker->boolean(30) ? \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_STAFF : \NorthEastEvents\Map\UserTableMap::COL_PERMISSION_NORMAL;
            },
        ));
        $populator->addEntity('\NorthEastEvents\Event', 15, array(
            'ImageUrl' => function () use ($faker) {
                return $faker->imageUrl(180, 180);
            },
            'Body' => function () use ($faker) {
                return $faker->realText(750);
            },
            'BodyHTML' => null,
            'Tickets' => function () use ($faker) {
                return $faker->numberBetween(20, 100);
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));

        $usersEventsIDs = $populator->execute();
        $response->getBody()->write(sprintf("Adding users & events took %.3fs<br/>", microtime(true) - $start));
        \NorthEastEvents\Bootstrap::getLogger()->addInfo(sprintf("[DATA] Added random users and events, taking %.3fs", microtime(true) - $start));

        $start = microtime(true);

        $populator = new ORM\Propel2\Populator($faker);
        $populator->addEntity('\NorthEastEvents\EventUsers', 200, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\User']);
                return $id[0];
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\Event']);
                return $id;
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ), array(
            function($eu) use ($faker, $usersEventsIDs) {
                $existing = \NorthEastEvents\EventUsersQuery::create()->findOneByArray(['EventID' => $eu->getEventID(), 'UserID' => $eu->getUserID()]);
                while($existing != null){
                    $eu->setEventID($faker->randomElement($usersEventsIDs['\NorthEastEvents\Event']));
                    $existing = \NorthEastEvents\EventUsersQuery::create()->findOneByArray(['EventID' => $eu->getEventID(), 'UserID' => $eu->getUserID()]);
                }
            }
        ));

        $populator->addEntity('\NorthEastEvents\EventRating', 75, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\User']);
                return $id[0];
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\Event']);
                return $id;
            },
            'Rating' => function () use ($faker, $usersEventsIDs) {
                $rating = $faker->randomElement([
                    \NorthEastEvents\Map\EventRatingTableMap::COL_RATING_1,
                    \NorthEastEvents\Map\EventRatingTableMap::COL_RATING_2,
                    \NorthEastEvents\Map\EventRatingTableMap::COL_RATING_3,
                    \NorthEastEvents\Map\EventRatingTableMap::COL_RATING_4,
                    \NorthEastEvents\Map\EventRatingTableMap::COL_RATING_5,
                ]);
                return $rating;
            },
        ), array(
            // Because there are several primary keys, we need to make sure they are unique together
            function($er) use ($faker, $usersEventsIDs) {
                $existing = \NorthEastEvents\EventRatingQuery::create()->findOneByArray(['EventID' => $er->getEventID(), 'UserID' => $er->getUserID()]);
                while($existing != null){
                    $er->setEventID($faker->randomElement($usersEventsIDs['\NorthEastEvents\Event']));
                    $existing = \NorthEastEvents\EventRatingQuery::create()->findOneByArray(['EventID' => $er->getEventID(), 'UserID' => $er->getUserID()]);
                }
            }
        ));

        $populator->addEntity('\NorthEastEvents\Thread', 15, array(
            'Title' => function () use ($faker) {
                return $faker->catchPhrase;
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                return $faker->randomElement($usersEventsIDs['\NorthEastEvents\Event']);
            },
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\User']);
                return $id[0];
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));
        $threads = $populator->execute();
        $populator = new ORM\Propel2\Populator($faker);

        $populator->addEntity('\NorthEastEvents\Comment', 50, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs['\NorthEastEvents\User']);
                return $id[0];
            },
            'ThreadID' => function () use ($faker, $threads) {
                return $faker->randomElement($threads['\NorthEastEvents\Thread']);
            },
            'Body' => function () use ($faker) {
                return $faker->catchPhrase;
            },
            'BodyHTML' => null,
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));

        $comments = $populator->execute();

        \NorthEastEvents\Bootstrap::getLogger()->addInfo(sprintf("[DATA] Connected all the dots between users and events, taking %.3fs", microtime(true) - $start));
        $response->getBody()->write(sprintf("Adding attending users to events took %.3fs<br/>", microtime(true) - $start));
        return $response;
    });

    $app->get('/example', function (Request $request, Response $response) {
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
}
