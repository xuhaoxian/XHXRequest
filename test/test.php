<?php
/**
 * Created by PhpStorm.
 * User: xuhaoxian
 * Date: 2018/5/7
 * Time: 下午10:25
 */

require_once __DIR__.'/../vendor/autoload.php';

\XHXRequest\XHXRequest::getInstance()->retryTimes = 3;
\XHXRequest\XHXRequest::getInstance()->header = array('aa'=>11);

$req = \XHXRequest\XHXRequest::getInstance()
    ->request('local.aimiaoyin.com/index', 'get', array(),
        function (\XHXRequest\Response $response){
        return '111';
        });
var_dump($req);