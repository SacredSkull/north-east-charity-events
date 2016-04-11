<?php
/**
 * Created by PhpStorm.
 * User: Peter
 * Date: 10/04/2016
 * Time: 18:45
 */

namespace NorthEastEvents\Controllers\Routes;


use Interop\Container\ContainerInterface;
use NorthEastEvents\Controllers\Controller;

abstract class Routes {
    protected $ci;
    protected $app;
    protected $controllerClass;

    public function __construct(ContainerInterface $ci, string $controllerClass) {
        $this->ci = $ci;
        $this->controllerClass = $controllerClass;
        $this->ci[$controllerClass] = function($ci) use ($controllerClass){
            $uc = new $controllerClass($ci);
            return $uc;
        };
        $this->app = $ci->get("slim");

        $this->routes();
    }

    public abstract function routes();
}