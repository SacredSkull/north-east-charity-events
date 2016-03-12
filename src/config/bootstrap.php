<?php

define('DEBUG', true);

date_default_timezone_set("Europe/London");

if(!file_exists(__DIR__ . "/../vendor")){
    echo "<p>The composer folder is missing! The website cannot run without its dependencies - try running <i>'vagrant provision'</i>, or manually attempt a fix with <i>'composer install'</i></p><br><br>.";
}

require __DIR__ . '/../vendor/autoload.php';

$settings = require __DIR__ . '/../config/slim/slim.php';
$app = new \Slim\App($settings);

// Get container
$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Register component on container
$container['view'] = function ($c) {
        $view = new \Slim\Views\Twig($c->get('settings')['renderer']['template_path'],
            ['cache' => DEBUG ? null : $c->get('settings')['renderer']['cache']
    ]);
    $view->addExtension(new \Slim\Views\TwigExtension(
        $c['router'],
        $c['request']->getUri()
    ));
    return $view;
};
