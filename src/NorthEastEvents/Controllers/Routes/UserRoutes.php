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
        $app->get('/users[/{page:[0-9]+}]', UserController::class.':GetUsers')
            ->setName("UsersList");

        // Single user
        $app->group('/user', function(){
            // Get current user details
            //TODO: Create GET method for /user to pull up the current user's details (i.e. omit the user ID)
            $this->get('', UserController::class.':UserOperations')
                ->setName("UserCurrentGET");

            // Create new user (register)
            $this->post('', UserController::class.':CreateUser')
                ->setName("UserCreate");

            // Operations on a specific user
            $this->group('/{userID:[0-9]+}', function(){
                // Get user details
                $this->get('[/{page:[0-9]+}]', UserController::class.':UserOperations')
                    ->setName("UserOperations");
            })->add(new AuthorisedRouteMiddleware($this->getContainer()));
        });

        // Create new user (register)
        $app->get('/register', UserController::class.':CreateUserGET')
            ->setName("UserCreateGET");

        $app->post('/register', UserController::class.':CreateUserPOST')
            ->setName("UserCreatePOST");

        // Login
        $app->post('/login', UserController::class.':LoginController')
            ->setName("UserLogin");

        // Logout
        //TODO: Logout
        $app->get('/logout', UserController::class.':LogoutController')
            ->setName("UserLogout");

        /**
         * API Controllers
         */

        $app->group('/api', function () {
            // User operations
            $this->group('/user', function(){
                // Get current user details
                //TODO: Create GET method for /user to pull up the current user's details (i.e. omit the user ID)
                $this->get('', UserController::class.':APIUserOperations')
                    ->setName("APIUserCurrentGET");


                // Create new user (register)
                $this->post('', UserController::class.':CreateUser')
                    ->setName("APIUserCreate");

                // Operations on a specific user
                $this->group('/{userID:[0-9]+}', function(){
                    // Get user details, and perform administrative operations on others
                    $this->map(["GET", "DELETE", "PUT", "PATCH"], '', UserController::class.':APIUserOperations')
                        ->setName("APIUserOperations")
                        ->add(new AuthorisedRouteMiddleware($this->getContainer()))->add(new BasicAuthMiddleware());

                    // Get events this user is publically attending
                    $this->get('/events', function($request, $response, $args){
                        return $response->getBody()->write("This should GET all PUBLICALLY subscribed (i.e. not private) events for user ID" . $args["userID"]);
                    });
                });
            });

            // Get list of users
            $this->get('/users', UserController::class.':APIGetUsers')
                ->setName("APIUsersList");

            // Register
            $this->post('/register', UserController::class.':CreateUser')
                ->setName("APIUserCreate");
        });
    }
}