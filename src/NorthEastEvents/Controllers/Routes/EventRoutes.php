<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 19:16
 */

namespace NorthEastEvents\Controllers\Routes;


class EventRoutes extends Routes {

    public function routes() {
        $app = $this->app;
        /**
         * Front-end Controllers
         */

        // All events
        $app->get('/events', '\NorthEastEvents\EventController:GetEvents')
            ->setName("EventsList");

        // Single event
        $app->group('/event', function(){
            // Create event
            $this->post('', '\NorthEastEvents\EventController:CreateEvent')
                ->setName("EventCreate");

            // Operations on a specific event
            $this->group('/{eventID:[0-9]+}', function(){
                // Get event details
                $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\EventController:EventOperations')
                    ->setName("EventOperations");

                // Get list of all publically attending users of an event
                $this->get('/users', '\NorthEastEvents\EventController:GetEventUsers')
                    ->setName("EventUsersGET");

                // Attend an event
                $this->get('/register', '\NorthEastEvents\EventController:RegisterEvent')
                    ->setName("APIEventRegister");

                // Stop attending an event
                $this->get('/deregister', '\NorthEastEvents\EventController:DeregisterEvent')
                    ->setName("APIEventDeregister");

                // Event comments will be on the page
            });
        });

        /**
         * API Controllers
         */

        $app->group('/api', function () {
            // All events
            $this->get('/events', '\NorthEastEvents\EventController:APIGetEvents')
                ->setName("APIEventsList");

            $this->group('/event', function() {
                // Create event
                $this->post('', '\NorthEastEvents\EventController:APICreateEvent')
                    ->setName("APIEventCreate");

                // Specific event operations
                $this->group('/{eventID:[0-9]+}',function(){

                    // Get/delete/put/patch specific event
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\EventController:APIEventOperations')
                        ->setName("APIEventOperations");

                    // Get the threads of an event
                    $this->get('/threads', '\NorthEastEvents\EventController:APIGetEventThreads')
                        ->setName("APIEventThreadsList");

                    // Attend an event
                    $this->get('/register', '\NorthEastEvents\EventController:APIRegisterEvent')
                        ->setName("APIEventRegister");

                    // Stop attending an event
                    $this->get('/deregister', '\NorthEastEvents\EventController:APIDeregisterEvent')
                        ->setName("APIEventDeregister");
                });
            });
        });
    }
}