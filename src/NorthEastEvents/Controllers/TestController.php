<?php

namespace NorthEastEvents\Controllers;

use Faker\Factory;
use NorthEastEvents\Models\Charity;
use NorthEastEvents\Models\CharityQuery;
use NorthEastEvents\Models\CommentQuery;
use NorthEastEvents\Models\EventRating;
use NorthEastEvents\Models\EventRatingQuery;
use NorthEastEvents\Models\EventUsers;
use NorthEastEvents\Models\ORM;
use NorthEastEvents\Models\Map;
use NorthEastEvents\Models\ThreadQuery;
use NorthEastEvents\Models\User;
use NorthEastEvents\Models\Event;
use NorthEastEvents\Models\Comment;
use NorthEastEvents\Models\Thread;
use NorthEastEvents\Models\EventQuery;
use NorthEastEvents\Models\EventUsersQuery;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use NorthEastEvents\Bootstrap;

class TestController extends Controller {
    public function DataHandler(Request $request, Response $response) {
        $faker = Factory::create("en_GB");
        if ($request->getAttribute('wipe')) {
            $start = microtime(true);
            \NorthEastEvents\Models\Base\UserQuery::create()->deleteAll();
            EventQuery::create()->deleteAll();
            EventUsersQuery::create()->deleteAll();
            EventRatingQuery::create()->deleteAll();
            CharityQuery::create()->deleteAll();
            ThreadQuery::create()->deleteAll();
            CommentQuery::create()->deleteAll();
            $response->getBody()->write(sprintf("Wiping users, events, and event bookings (Event Users) took %.3fs<br/>", microtime(true) - $start));
            Bootstrap::getLogger()->addInfo("[DATA] Wiped users, events and everything else...");
        }

        $start = microtime(true);
        $populator = new ORM\Propel2\Populator($faker);
        $populator->addEntity(User::class, 50, array(
            'AvatarUrl' => function () use ($faker) {
                return $faker->imageUrl(180, 180);
            },
            'Bio' => function () use ($faker) {
                return $faker->realText(500);
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
            'Permission' => function () use ($faker) {
                return $faker->boolean(15) ? Map\UserTableMap::COL_PERMISSION_STAFF : Map\UserTableMap::COL_PERMISSION_NORMAL;
            },
        ));

        $populator->addEntity(Charity::class, 50, array(
            'Logo' => function () use ($faker) {
                return $faker->imageUrl(200, 100);
            },
            'Bio' => function () use ($faker) {
                return $faker->realText(500);
            },
            'Name' => function () use ($faker) {
                return $faker->company;
            },
            'UpdatedAt' => null,
            'CreatedAt' => null,
        ));

        $usersEventsIDs = $populator->execute();
        $populator = new ORM\Propel2\Populator($faker);

        $populator->addEntity(Event::class, 15, array(
            'Title' => function () use ($faker) {
                return $faker->catchPhrase;
            },
            'Location' => function () use ($faker) {
                return $faker->address;
            },
            'Date' => function () use ($faker){
                return $faker->dateTimeBetween('-2 days', '+3 days');
            },
            'ImageUrl' => function () use ($faker) {
                return $faker->imageUrl(350, 240);
            },
            'Body' => function () use ($faker) {
                return $faker->realText(1600);
            },
            'CharityID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[Charity::class]);
                return $id;
            },
            'BodyHTML' => null,
            'Tickets' => function () use ($faker) {
                return $faker->numberBetween(20, 100);
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));

        $usersEventsIDs = array_merge($usersEventsIDs, $populator->execute());
        $response->getBody()->write(sprintf("Adding users, charities & events took %.3fs<br/>", microtime(true) - $start));
        Bootstrap::getLogger()->addInfo(sprintf("[DATA] Added random users and events, taking %.3fs", microtime(true) - $start));

        $start = microtime(true);

        $populator = new ORM\Propel2\Populator($faker);

        $populator->addEntity(EventUsers::class, 200, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[User::class]);
                return $id[0];
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[Event::class]);
                return $id;
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ), array(
            function(EventUsers $eu) use ($faker, $usersEventsIDs) {
                $existing = EventUsersQuery::create()->findOneByArray(['EventID' => $eu->getEventID(), 'UserID' => $eu->getUserID()]);
                while($existing != null){
                    $eu->setEventID($faker->randomElement($usersEventsIDs[Event::class]));
                    $existing = EventUsersQuery::create()->findOneByArray(['EventID' => $eu->getEventID(), 'UserID' => $eu->getUserID()]);
                }
            }
        ));

        $populator->addEntity(EventRating::class, 75, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[User::class]);
                return $id[0];
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[Event::class]);
                return $id;
            },
            'Rating' => function () use ($faker, $usersEventsIDs) {
                $rating = $faker->randomElement([
                    Map\EventRatingTableMap::COL_RATING_1,
                    Map\EventRatingTableMap::COL_RATING_2,
                    Map\EventRatingTableMap::COL_RATING_3,
                    Map\EventRatingTableMap::COL_RATING_4,
                    Map\EventRatingTableMap::COL_RATING_5,
                ]);
                return $rating;
            },
        ), array(
            // Because there are several primary keys, we need to make sure they are unique together
            function(EventRating $er) use ($faker, $usersEventsIDs) {
                $existing = EventRatingQuery::create()->findOneByArray(['EventID' => $er->getEventID(), 'UserID' => $er->getUserID()]);
                while($existing != null){
                    $er->setEventID($faker->randomElement($usersEventsIDs[Event::class]));
                    $existing = EventRatingQuery::create()->findOneByArray(['EventID' => $er->getEventID(), 'UserID' => $er->getUserID()]);
                }
            }
        ));

        $populator->addEntity(Thread::class, 15, array(
            'Title' => function () use ($faker) {
                return $faker->catchPhrase;
            },
            'EventID' => function () use ($faker, $usersEventsIDs) {
                return $faker->randomElement($usersEventsIDs[Event::class]);
            },
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[User::class]);
                return $id[0];
            },
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));
        $threads = $populator->execute();
        $populator = new ORM\Propel2\Populator($faker);

        $populator->addEntity(Comment::class, 50, array(
            'UserID' => function () use ($faker, $usersEventsIDs) {
                $id = $faker->randomElement($usersEventsIDs[User::class]);
                return $id[0];
            },
            'ThreadID' => function () use ($faker, $threads) {
                return $faker->randomElement($threads[Thread::class]);
            },
            'Body' => function () use ($faker) {
                return $faker->catchPhrase;
            },
            'BodyHTML' => null,
            'CreatedAt' => null,
            'UpdatedAt' => null,
        ));

        $admin = new User();
        $admin->setUsername("admin")->setPassword("admin")->setEmail("admin@whatever.com")->setPermission(Map\UserTableMap::COL_PERMISSION_STAFF)->save();

        $comments = $populator->execute();

        Bootstrap::getLogger()->addInfo(sprintf("[DATA] Connected all the dots between users and events, taking %.3fs", microtime(true) - $start));
        $response->getBody()->write(sprintf("Adding attending users to events took %.3fs<br/>", microtime(true) - $start));
        return $response;
    }
    
    public function Example(Request $request, Response $response) {
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
    
        return $this->ci->get("view")->render($response, "example.twig.html", $this->renderVariables([
            'navigation' => $example,
            'variable_name' => "Twig variable example"
        ]));
    }

    public function PHPInfo(Request $request, Response $response){
        phpinfo();
    }
}