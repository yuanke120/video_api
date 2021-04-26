<?php
namespace app\lib\exception;

/**
 * token验证失败时抛出异常
 */
class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '用户权限不够';
    public $errorCode =10001;
}