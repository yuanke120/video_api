<?php
namespace app\lib\exception;

class BannerMissException extends BaseException
{
    public $code  = 500;
    public $msg ="请求短视频列表不存在";
     public $errorCode = 40000;
}