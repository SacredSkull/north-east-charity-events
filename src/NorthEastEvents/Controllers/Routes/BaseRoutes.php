<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 18:55
 */

namespace NorthEastEvents\Controllers\Routes;


use NorthEastEvents\Controllers\BaseController;

class BaseRoutes extends Routes {
    public function routes() {
        $app = $this->app;
        $app->get('/', BaseController::class.":Base")->setName("Home");
        // TODO: /contact
    }
}