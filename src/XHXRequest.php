<?php
/**
 * Created by PhpStorm.
 * User: xuhaoxian
 * Date: 2018/3/17
 * Time: 上午9:16
 */

namespace XHXRequest;


use XHXRequest\Request;
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
    public $retryTimes = 0;
    //post请求方式
    public $contentType = self::CONTENT_TYPE_JSON;
    //请求头部header
    public $header = array();
    //设置opt
    public $curlOpt = [];

    public function __construct()
    {
    }

    public function request(string $url, string $method, array $data = array(),
                            callable $successHandler, callable $errorHandler = null, callable $completeHandler = null){
        $method = strtoupper($method);

        $request = new Request($url);
        if (!empty($this->curlOpt)) {
            $request->setUserOpt($this->curlOpt);
        }
        //请求方式
        switch ($method){
            case 'GET':
                $request->query = $data;
                break;
            case 'POST':
                $request->body = $data;
                break;
            case 'PUT':
                $request->body = $data;
                $request->setUserOpt([CURLOPT_CUSTOMREQUEST=>"put"]);
                break;
            case 'DELETE':
                $request->body = $data;
                $request->setUserOpt([CURLOPT_CUSTOMREQUEST=>"delete"]);
                break;
            default:
                throw new \InvalidArgumentException('method error');
                break;
        }

        //外部没有指定header的contenttype就用内部默认
        $header['Content-Type'] = $this->header['Content-Type'] ?? $this->contentType;
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


        //设置请求头部
        if (!empty($header) && is_array($header)){
            foreach ($header as $key=>$value){
                $string = "$key:$value";
                $headerArr[] = $string;
            }
            $request->setUserOpt([CURLOPT_HTTPHEADER => $headerArr]);
        }

        $times = $this->retryTimes + 1;
        while ($times > 0){
            $times--;

            $resp = $request->exec();
            //curl 请求失败处理
            if ($resp->getErrorNo()){
                if (is_callable($errorHandler)){
                    $errorReturn = call_user_func($errorHandler, $resp);
                }else{
                    $errorReturn = null;
                }
                if ($times > 0){
                    continue;
                }
                return $this->requestReturn('0', $errorReturn);
            }

            $httpCode = $resp->getCurlInfo()['http_code'];
            //响应错误码处理
            if (!in_array($httpCode, $this->successCode)){
                if (is_callable($errorHandler)){
                    $errorReturn = call_user_func($errorHandler, $resp);
                }else{
                    $errorReturn = null;
                }
                if ($times > 0){
                    continue;
                }
                return $this->requestReturn('0', $errorReturn);
            }else {
                $successReturn = call_user_func($successHandler, $resp);
            }

            if (is_callable($completeHandler)){
                $completeReturn = call_user_func($completeHandler, $resp);
            }
            return $this->requestReturn('1', null, $successReturn, $completeReturn);

        }
    }

    private function requestReturn
    ($code, $errorCallbackReturn = null, $successCallbackReturn = null, $completeCallbackReturn = null){
        return [
            'code'=>$code,
            'errorCallbackReturn'=>$errorCallbackReturn,
            'successCallbackReturn'=>$successCallbackReturn,
            'completeCallbackReturn'=>$completeCallbackReturn
        ];
    }

}