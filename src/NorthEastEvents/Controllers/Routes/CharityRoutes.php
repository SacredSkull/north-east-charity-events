<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 17/04/2016
 * Time: 21:49
 */

namespace NorthEastEvents\Controllers\Routes;


use NorthEastEvents\Controllers\CharityController;
use NorthEastEvents\Middleware\AuthorisedRouteMiddleware;

class CharityRoutes extends Routes
{

    public function routes() {
        $app = $this->app;

        $app->get('/charities[/{page:[0-9]+}]', CharityController::class.":CharitiesList");

        $app->group('/charity', function(){
            // Create new Charity
            $this->get('/create', CharityController::class.':CreateCharityGET')
                ->setName("CharityCreateGET");

            $this->post('/create', CharityController::class.':CreateCharityPOST')
                ->setName("CharityCreatePOST")->add(new AuthorisedRouteMiddleware($this->getContainer()));

            // Operations on a specific user
            $this->group('/{charityID:[0-9]+}', function(){
                // Get user details
                $this->get('', CharityController::class.':CharityOperations')
                    ->setName("CharityOperations");
            });
        });
    }
}