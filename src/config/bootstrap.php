<?php


namespace NorthEastEvents {

    use Monolog\Handler\StreamHandler;
    use Monolog\Logger;
    use Monolog\Processor\UidProcessor;

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/propel/generated-conf/config.php';

    class Bootstrap {

        const DEBUG = true;
        private static $_slim = null;

        public static function getSlim(){
            if(static::$_slim == null){
                static::initialise();
            }
            return static::$_slim;
        }

        private static function initialise() {
            date_default_timezone_set("Europe/London");

            if (!file_exists(__DIR__ . "/../vendor")) {
                echo "<p>The composer folder is missing! The website cannot run without its dependencies - try running <i>'vagrant provision'</i>, or manually attempt a fix with <i>'composer install'</i></p><br><br>.";
            }

            $settings = require __DIR__ . '/slim/slim.php';
            static::$_slim = new \Slim\App($settings);

            // Get container
            $container = static::$_slim->getContainer();

            // monolog
            $container['logger'] = function ($c) {
                $settings = $c->get('settings')['logger'];
                $logger = new Logger($settings['name']);
                $logger->pushProcessor(new UidProcessor());
                $logger->pushHandler(new StreamHandler($settings['path'], Logger::DEBUG));
                return $logger;
            };

            // Register component on container
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
    }
}

//namespace {
//    NorthEastEvents\Bootstrap::initialise();
//}