<?php
/**
 * Created by PhpStorm.
 * User: xuhaoxian
 * Date: 2018/3/16
 * Time: 上午11:37
 */

namespace XHXRequest;

trait Singleton
{
    private static $instance;

    static function getInstance(...$args)
    {
        if(!isset(self::$instance)){
            self::$instance = new static(...$args);
        }
        return self::$instance;
    }
}