<?php
namespace app\lib\exception;

use Exception;
use think\exception\Handle;
use think\Log;
use think\Request;

/**
 * Class ExceptionHandler
 * @package app\lib\exception
 */
class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    public function render(\Exception $e)
    {
        if($e instanceof BaseException){
            $this->code=$e->code;
            $this->msg=$e->msg;
            $this->errorCode=$e->errorCode;
        }else{
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->code=500;
                $this->msg='服务器内部错误';
                $this->errorCode=999;
                $this->recordErrorLog($e);
            }
        }
        $request=Request::instance();
        $result=[
            'msg'=>$this->msg,
            'error_code'=>$this->errorCode,
            'request_url'=>$request->url(),
        ];
        return json($result,$this->code);
    }

    //错误日志
    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
}