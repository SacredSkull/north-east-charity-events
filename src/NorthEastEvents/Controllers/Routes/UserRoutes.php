<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 18:29
 */

namespace NorthEastEvents\Controllers\Routes;

use NorthEastEvents\Controllers\UserController;
use NorthEastEvents\Middleware\AuthorisedRouteMiddleware;
use NorthEastEvents\Middleware\BasicAuthMiddleware;

class UserRoutes extends Routes{
    public function routes(){
        $app = $this->app;

        /**
         * Front-end Controllers
         */
        // All users
        $app->get('/users', '\NorthEastEvents\Controllers\UserController:GetUsers')
            ->setName("UsersList");

        // Single user
        $app->group('/user', function(){
            // Get current user details
            //TODO: Create GET method for /user to pull up the current user's details (i.e. omit the user ID)
            $this->get('', '\NorthEastEvents\Controllers\UserController:UserOperations')
                ->setName("UserCurrentGET");

            // Create new user (register)
            $this->post('', '\NorthEastEvents\Controllers\UserController:CreateUser')
                ->setName("UserCreate");

            $this->get('/events', function($request, $response, $args){
                return $response->getBody()->write("This should GET all PUBLICALLY subscribed (i.e. not private) events for user ID - unless you are viewing your own" . $args["userID"]);
            })->setName("UserCurrentEventsGET");

            // Operations on a specific user
            $this->group('/{userID:[0-9]+}', function(){
                // Get user details
                $this->get('', '\NorthEastEvents\Controllers\UserController:UserOperations')
                    ->setName("UserOperations");
            });
        });

        // Create new user (register)
        $app->post('/register', '\NorthEastEvents\Controllers\UserController:CreateUser')
            ->setName("UserCreate");

        // Login
        $app->post('/login', '\NorthEastEvents\Controllers\UserController:LoginController')
            ->setName("UserLogin");

        // Logout
        //TODO: Logout
        $app->post('/logout', '\NorthEastEvents\Controllers\UserController:LogoutController')
            ->setName("UserLogout");

        /**
         * API Controllers
         */

        $app->group('/api', function () {
            // User operations
            $this->group('/user', function(){
                // Get current user details
                //TODO: Create GET method for /user to pull up the current user's details (i.e. omit the user ID)
                $this->get('', '\NorthEastEvents\Controllers\UserController:APIUserOperations')
                    ->setName("APIUserCurrentGET");


                // Create new user (register)
                $this->post('', '\NorthEastEvents\Controllers\UserController:CreateUser')
                    ->setName("APIUserCreate");

                // Operations on a specific user
                $this->group('/{userID:[0-9]+}', function(){
                    // Get user details, and perform administrative operations on others
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', '\NorthEastEvents\Controllers\UserController:APIUserOperations')
                        ->setName("APIUserOperations")
                        ->add(new AuthorisedRouteMiddleware())->add(new BasicAuthMiddleware());

                    // Get events this user is publically attending
                    $this->get('/events', function($request, $response, $args){
                        return $response->getBody()->write("This should GET all PUBLICALLY subscribed (i.e. not private) events for user ID" . $args["userID"]);
                    });
                });
            });

            // Get list of users
            $this->get('/users', '\NorthEastEvents\Controllers\UserController:APIGetUsers')
                ->setName("APIUsersList");

            // Register
            $this->post('/register', '\NorthEastEvents\Controllers\UserController:CreateUser')
                ->setName("APIUserCreate");
        });
    }
}