<?php

namespace NorthEastEvents;

use Ciconia\Ciconia;
use Ciconia\Extension\Gfm;

class CiconiaSingleton{
    private static $ciconiaInstance = null;

    public static function get(){
        if(static::$ciconiaInstance == null){
            $ciconiaInstance = new Ciconia();
            $ciconiaInstance->addExtension(new Gfm\FencedCodeBlockExtension());
            $ciconiaInstance->addExtension(new Gfm\TaskListExtension());
            $ciconiaInstance->addExtension(new Gfm\InlineStyleExtension());
            $ciconiaInstance->addExtension(new Gfm\WhiteSpaceExtension());
            $ciconiaInstance->addExtension(new Gfm\TableExtension());
            $ciconiaInstance->addExtension(new Gfm\UrlAutoLinkExtension());
        }
        return static::$ciconiaInstance;
    }
}