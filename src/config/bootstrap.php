<?php

namespace NorthEastEvents {

    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/propel/generated-conf/config.php';

    use Monolog\Handler\StreamHandler;
    use Monolog\Logger;
    use Monolog\Processor\UidProcessor;
    use Ciconia\Ciconia;
    use Ciconia\Extension\Gfm;

    class Bootstrap {
        const DEBUG = true;

        private static $_slim = null;
        private static $_session = null;
        private static $_ciconia = null;

        public static function getSlim(){
            if(static::$_slim == null)
                static::initialise();
            return static::$_slim;
        }

        public static function getSession(){
            if(static::$_session == null)
                static::initialise();
            return static::$_session;
        }

        public static function getLoginSession(){
            if(static::$_session == null)
                static::initialise();
            return static::$_session->getSegment('NorthEastEvents\Login');
        }

        public static function getLogger(){
            return static::getSlim()->getContainer()["logger"];
        }

        public static function getCiconia(){
            if(static::$_ciconia == null){
                static::$_ciconia = new Ciconia();
                static::$_ciconia->addExtension(new Gfm\FencedCodeBlockExtension());
                static::$_ciconia->addExtension(new Gfm\TaskListExtension());
                static::$_ciconia->addExtension(new Gfm\InlineStyleExtension());
                static::$_ciconia->addExtension(new Gfm\WhiteSpaceExtension());
                static::$_ciconia->addExtension(new Gfm\TableExtension());
                static::$_ciconia->addExtension(new Gfm\UrlAutoLinkExtension());
            }
            return static::$_ciconia;
        }

        private static function initialise() {
            date_default_timezone_set("Europe/London");

            if (!file_exists(__DIR__ . "/../vendor"))
                echo "<p>The composer folder is missing! The website cannot run without its dependencies - try running".
                    "<i>'vagrant provision'</i>, or manually attempt a fix with <i>'composer install'</i></p><br><br>.";

            self::initSlim();
            self::initSession();
        }

        private static function initSlim(){
            $settings = require __DIR__ . '/slim/slim.php';
            static::$_slim = new \Slim\App($settings);
            // Get container
            $container = static::$_slim->getContainer();

            // Monolog
            $container['logger'] = function ($c) {
                $settings = $c->get('settings')['logger'];
                $logger = new Logger($settings['name']);
                $logger->pushProcessor(new UidProcessor());
                $logger->pushHandler(new StreamHandler($settings['path'], Logger::DEBUG));
                return $logger;
            };

            // Twig
            $container['view'] = function ($c) {
                $view = new \Slim\Views\Twig($c->get('settings')['renderer']['template_path'],
                    ['cache' => self::DEBUG ? null : $c->get('settings')['renderer']['cache']
                    ]);
                $view->addExtension(new \Slim\Views\TwigExtension(
                    $c['router'],
                    $c['request']->getUri()
                ));
                return $view;
            };
        }

        private static function initSession(){
            // Setup sessions
            $sessionFactory = new \Aura\Session\SessionFactory;
            static::$_session = $sessionFactory->newInstance($_COOKIE);

            // Setup specific session segments
            // Login segment
            static::$_session->getSegment('NorthEastEvents\Login');
        }
    }
}

namespace{

    use NorthEastEvents\Bootstrap;
    $app = Bootstrap::getSlim();
}
