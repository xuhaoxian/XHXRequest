<?php

require_once __DIR__ . '/../vendor/autoload.php';

$query = array();
$params = array("account"=>"13606910469", "password"=>"123123");
$header = array();

\XHXRequest\XHXRequest::getInstance()->request(
    'api1.inmimusic.cn/api/user/auth/login',
    'post',
    $query,
    $params,
    $header,
    function (\XHXRequest\Response $resp){
        var_dump($resp->getBody());
    },
    function (\XHXRequest\Response $resp){
        var_dump($resp);
    }
);
