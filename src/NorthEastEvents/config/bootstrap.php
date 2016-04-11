<?php

namespace NorthEastEvents;

require_once __DIR__ . '/../vendor/autoload.php';
//if(getenv("TESTING") != true)
    require_once __DIR__ . '/propel/generated-conf/config.php';
//else
//    require_once __DIR__ . '/propel/generated-conf/test-config.php';

use Aura\Session\SessionFactory;
use Interop\Container\ContainerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;
use NorthEastEvents\Models\User;
use Slim\App;
use Slim\Views\Twig;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class Bootstrap {
    private static $ci;
    const DEBUG = true;

    /**
     * @return App
     */
    public function __invoke() {
        return $this->initialise();
    }

    public function initialise() {
        if (!file_exists(__DIR__ . "/../vendor"))
            echo "<p>The composer folder is missing! The website cannot run without its dependencies - try running".
                "<i>an upgrade script (e.g. upgrade.bat)</i>, or manually attempt a fix with <i>'composer install' inside a docker instance.</i></p><br><br>.";

        $settings = require __DIR__ . '/slim/slim.php';
        $slim = new \Slim\App($settings);
        // Get container
        static::$ci = $slim->getContainer();

        /**
         * @param ContainerInterface $c
         * @return App
         */
        static::$ci['slim'] = function (ContainerInterface $c) use($slim) {
            return $slim;
        };

        $this->initDependencies();
        $this->initHandlers();

        return $slim;
    }

    public function initDependencies(){
        /**
         * @param ContainerInterface $c
         * @return Logger
         */
        static::$ci['logger'] = function (ContainerInterface $c) {
            $settings = $c->get('settings')['logger'];
            $logger = new Logger($settings['name']);
            $logger->pushProcessor(new UidProcessor());
            $logger->pushHandler(new StreamHandler($settings['path'], Logger::DEBUG));
            return $logger;
        };

        /**
         * @param ContainerInterface $c
         * @return Twig
         */
        static::$ci['view'] = function (ContainerInterface $c) {
            $view = new Twig($c->get('settings')['renderer']['template_path'],
                [ 'cache' => self::DEBUG ? null : $c->get('settings')['renderer']['cache'] ]
            );
            $view->addExtension(new \Slim\Views\TwigExtension(
                $c['router'],
                $c['request']->getUri()
            ));
            return $view;
        };

        /**
         * @param ContainerInterface $c
         * @return \Aura\Session\Session
         */
        static::$ci['session'] = function (ContainerInterface $c){
            $session_factory = new SessionFactory();
            $session = $session_factory->newInstance($_COOKIE);
            $session->getSegment('NorthEastEvents\Login');
            return $session;
        };

        /**
         * @param ContainerInterface $c
         * @return User
         */
        static::$ci['current_user'] = function (ContainerInterface $c){
            return $c->get("session")->getSegment('NorthEastEvents\Login')->get("user", null);
        };

        /**
         * @param ContainerInterface $c
         * @return Ciconia
         */
        static::$ci['ciconia'] = function (ContainerInterface $c){
            $ciconia = new Ciconia();
            $ciconia->addExtension(new Gfm\FencedCodeBlockExtension());
            $ciconia->addExtension(new Gfm\TaskListExtension());
            $ciconia->addExtension(new Gfm\InlineStyleExtension());
            $ciconia->addExtension(new Gfm\WhiteSpaceExtension());
            $ciconia->addExtension(new Gfm\TableExtension());
            $ciconia->addExtension(new Gfm\UrlAutoLinkExtension());
            return $ciconia;
        };
    }

    private function initHandlers(){
        // 404 handler
        static::$ci['notFoundHandler'] = function (ContainerInterface $ci) {
            return function (Request $req, Response $res) use ($ci) {
                $path = explode("/", substr($req->getUri()->getPath(), 1));
                return $path[0] == "api" ?
                    $res->withJson(["Error" => ["Message" => "Resource was not found."]], 404) :
                    $ci->get("view")->render($res, "/errors/404.twig.html", [
                        "error_message" => "Could not find what you were looking for.",
                    ])->withStatus(404);
            };
        };

        // 405 handler
        static::$ci['notAllowedHandler'] = function (ContainerInterface $ci) {
            return function (Request $req, Response $res) use ($ci) {
                $path = explode("/", substr($req->getUri()->getPath(), 1));
                return $path[0] == "api" ?
                    $res->withJson(["Error" => ["Message" => "The requested method is not supported on this resource."]], 405) :
                    $ci->get("view")->render($res, "/errors/405.twig.html", [
                        "error_message" => "Sorry, we can't do that.",
                    ])->withStatus(405);
            };
        };
    }

    public static function getLogger(){
        return static::$ci->get("logger");
    }
}
//
//$bootstrap = new Bootstrap();
//$app = $bootstrap->initialise();
