<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 19:16
 */

namespace NorthEastEvents\Controllers\Routes;


use NorthEastEvents\Controllers\EventController;

class EventRoutes extends Routes {

    public function routes() {
        $app = $this->app;
        /**
         * Front-end Controllers
         */

        // All events
        $app->get('/events', EventController::class.':GetEvents')
            ->setName("EventsList");

        // Single event
        $app->group('/event', function(){
            // Create event
            $this->get('/create', EventController::class.':CreateEventGet')
                ->setName("EventCreateGET");

            $this->post('/create', EventController::class.':CreateEventPost')
                ->setName("EventCreatePOST");

            // Operations on a specific event
            $this->group('/{eventID:[0-9]+}', function(){
                // Get event details
                $this->map(["GET", "DELETE", "PUT", "PATCH"], '', EventController::class.':EventOperations')
                    ->setName("EventOperations");

                // Get list of all publically attending users of an event
                $this->get('/users', EventController::class.':GetEventUsers')
                    ->setName("EventUsersGET");

                // Attend an event
                $this->get('/register', EventController::class.':RegisterEvent')
                    ->setName("APIEventRegister");

                // Stop attending an event
                $this->get('/deregister', EventController::class.':DeregisterEvent')
                    ->setName("APIEventDeregister");

                // Event comments will be on the page
            });
        });

        /**
         * API Controllers
         */

        $app->group('/api', function () {
            // All events
            $this->get('/events', EventController::class.':APIGetEvents')
                ->setName("APIEventsList");

            $this->group('/event', function() {
                // Create event
                $this->post('', EventController::class.':APICreateEvent')
                    ->setName("APIEventCreate");

                // Specific event operations
                $this->group('/{eventID:[0-9]+}',function(){

                    // Get/delete/put/patch specific event
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', EventController::class.':APIEventOperations')
                        ->setName("APIEventOperations");

                    // Get the threads of an event
                    $this->get('/threads', EventController::class.':APIGetEventThreads')
                        ->setName("APIEventThreadsList");

                    // Attend an event
                    $this->get('/register', EventController::class.':APIRegisterEvent')
                        ->setName("APIEventRegister");

                    // Stop attending an event
                    $this->get('/deregister', EventController::class.':APIDeregisterEvent')
                        ->setName("APIEventDeregister");
                });
            });
        });
    }
}