<?php
namespace app\lib\exception;

class VideoException extends BaseException
{
    public $code=404;
    public $msg='请求短视频id不存在,请检查参数';
    public $errorCode=30000;
}