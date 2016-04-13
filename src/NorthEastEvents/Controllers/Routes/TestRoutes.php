<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 19:17
 */

namespace NorthEastEvents\Controllers\Routes;


class TestRoutes extends Routes {

    public function routes() {
        $app = $this->app;
        
        $app->get('/data[/{wipe}]', '\NorthEastEvents\Controllers\TestController:DataHandler');
        $app->get('/example', '\NorthEastEvents\Controllers\TestController:Example');
        $app->get('/phpinfo', '\NorthEastEvents\Controllers\TestController:PHPInfo');
    }
}