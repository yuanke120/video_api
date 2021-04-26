<?php
namespace app\lib\exception;

/**
 * 201 创建/更新成功，202需要一个异步的处理才能完成请求
 */
class SuccessMessage extends  BaseException
{
    public $code = 201;
    public $msg = '个人资料修改成功';
    public $errorCode = 0;
}