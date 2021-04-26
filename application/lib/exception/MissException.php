<?php
namespace app\lib\exception;

class MissException extends BaseException
{
    public $code =404;
    public $msg = '全局找不到资源404';
    public $errorCode=10001;
}