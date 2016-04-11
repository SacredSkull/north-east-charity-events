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
        
        $app->get('/data[/{wipe}]', '\NorthEastEvents\TestController:DataHandler');
        $app->get('/example', '\NorthEastEvents\TestController:Example');
        $app->get('/phpinfo', '\NorthEastEvents\TestController:PHPInfo');
    }
}