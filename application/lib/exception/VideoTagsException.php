<?php
namespace app\lib\exception;

class VideoTagsException extends BaseException
{
    public $code=404;
    public $msg='请求话题标签id不存在,请检查参数 or 话题没建立';
    public $errorCode=30000;
}