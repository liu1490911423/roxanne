<?php

namespace Roxanne\LaravelBase\Common\Exception\Exceptions;

use Exception;

class BusinessException extends Exception
{

    private $httpCode = 200;

    private $error_data;

    private $_code;

    private $prompt;

    private $urlType;

    private $url;

    public function __construct($cause = 'BusinessException.', $code, $data = [], $prompt, $urlType, $url)
    {
        parent::__construct($cause);
        $this->_code = $code;
        $this->error_data = $data;
        $this->prompt = $prompt;
        $this->url = $url;
        $this->urlType = $urlType;
    }

    public function getStatusCode()
    {
        return $this->_code;
    }

    /**
     * FunctionName：getResponse
     * Description：组装错误返回格式
     * Author：cherish
     * @param:data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getResponse()
    {
        $this->getRoute();
        $ret = [
            'message' => trans($this->getMessage(), $this->error_data),
            'data' => $this->error_data,
            'code' => $this->_code,
            'prompt' => $this->prompt,
            'url_type' => $this->urlType,
            'url' => $this->url,
            'type' => 'business' //此参数验证是否为业务错误
        ];
        return response()->json($ret, $this->httpCode);
    }

    public function getRoute()
    {
        if (request()->route()->getActionName() == 'YiluTech\YiMQ\Http\Controllers\YiMqController@run') {
            $this->httpCode = 400;
        }
    }
}
