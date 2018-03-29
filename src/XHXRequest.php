<?php
/**
 * Created by PhpStorm.
 * User: xuhaoxian
 * Date: 2018/3/17
 * Time: 上午9:16
 */

namespace XHXRequest;


use XHXRequest\Request;
use XHXRequest\Response;
use XHXRequest\Singleton;

class XHXRequest
{
    use Singleton;

    const CONTENT_TYPE_JSON = 'application/json; charset=utf-8';
    const CONTENT_TYPE_URLENCODE = 'application/x-www-form-urlencoded; charset=utf-8';
    const CONTENT_TYPE_FORMDATA = 'application/form-data; charset=utf-8';

    //成功状态码
    public $successCode = array('200');
    //重试次数
    public $retryTimes;
    //post请求方式
    public $contentType = self::CONTENT_TYPE_JSON;

    public function __construct()
    {
    }

    public function request(string $url, string $method, array $queryParams=array(), array $bodyParams=array(), array $header=array(),
                            callable $successHandler, callable $errorHandler, callable $completeHandler = null){
        $method = strtoupper($method);

        $request = new Request($url);
        switch ($method){
            case 'GET':
                $request->query = $queryParams;
                break;
            case 'POST':
                $request->query = $queryParams;
                $request->body = $bodyParams;
                break;
            case 'PUT':
                $request->query = $queryParams;
                $request->body = $bodyParams;
                $request->setUserOpt([CURLOPT_CUSTOMREQUEST=>"put"]);
                break;
            case 'DELETE':
                $request->query = $queryParams;
                $request->body = $bodyParams;
                $request->setUserOpt([CURLOPT_CUSTOMREQUEST=>"delete"]);
                break;
            default:
                throw new \InvalidArgumentException('method error');
                break;
        }

        //外部没有指定header的contenttype就用内部默认
        $header['Content-Type'] = isset($header['Content-Type']) ? $header['Content-Type'] : $this->contentType;
        switch ($header['Content-Type']){
            case self::CONTENT_TYPE_FORMDATA:
                break;
            case self::CONTENT_TYPE_URLENCODE:
                $request->body = http_build_query($request->body);
                break;
            case self::CONTENT_TYPE_JSON:
                $request->body = json_encode($request->body);
                break;
            default:
                throw new \InvalidArgumentException('content-type error');
                break;
        }


        if (!empty($header) && is_array($header)){

            foreach ($header as $key=>$value){
                $string = "$key:$value";
                $headerArr[] = $string;
            }
            $request->setUserOpt([CURLOPT_HTTPHEADER => $headerArr]);
        }

        $resp = $request->exec();
        if ($resp->getErrorNo()){
            call_user_func($errorHandler, $resp);
            return;
        }
        if ($resp->getCurlInfo()['http_code'] != 200){
            call_user_func($errorHandler, $resp);
            return;
        }else {
            call_user_func($successHandler, $resp);
        }

        if (is_callable($completeHandler)){
            call_user_func($completeHandler, $resp);
        }

        return;
    }

}