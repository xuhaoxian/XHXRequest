XHXRequest
===
php写的基于curl封装的http请求器
---

特点：
1. 支持成功http_code设置
2. 成功失败回调都用闭包形式
3. 支持失败重试功能，重试次数可以设置

示例代码
---

```
//简单请求，成功回调 闭包形式
XHXRequest::getInstance()->request($url, 'get', array(),
            function (Response $response){
                $res = $response->getBody();
                $this->res = json_decode($res, true);
                return $res;
            });
            

//成功、失败、完成回调处理 并返回结果  
$result = XHXRequest::getInstance()->request($url, 'get', array(),
            function (Response $response){
                $res = $response->getBody();
                $this->res = json_decode($res, true);
                return $res;
            }，
            function (Response $response){
                return 'request fail';
            },
            function (Response $response){
                return 'request complete';
            });

var_dump($result);

//成功返回
[
    'code'=>1,
    'errorCallbackReturn'=>null,
    'successCallbackReturn'=>[...],
    'completeCallbackReturn'=>'request complete'
]

//失败返回
[
    'code'=>0,
    'errorCallbackReturn'=>'request fail',
    'successCallbackReturn'=>null,
    'completeCallbackReturn'=>'request complete'
]         

```

全局参数设置
---
````
//设置失败重试次数
XHXRequest::getInstance()->retryTimes = 3;

//设置curlOpt
XHXRequest::getInstance()->curlOpt = [];

//设置请求头部
XHXRequest::getInstance()->header = [];

//设置成功的 http_code
XHXRequest::getInstance()->successCode = ['200', '201'];
````


&copy; 312263441@qq.com