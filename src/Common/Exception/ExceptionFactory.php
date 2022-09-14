<?php
/**
 * Created by PhpStorm.
 * User: lishu
 * Date: 2018/10/11
 * Time: 15:39
 */
namespace Roxanne\LaravelBase\Common\Exception;



use Roxanne\LaravelBase\Common\Exception\Exceptions\BusinessException;

class ExceptionFactory
{
    public function business(array $mixed, array $data = [], int $prompt = 0, string $urlType = '', string $url = ''){
        return new BusinessException($mixed['message'] ?? 'business_error', $mixed['code'] ?? 400, $data, $prompt, $urlType, $url);
    }

}