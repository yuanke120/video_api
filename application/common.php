<?php
/**
 * JSON接口返回值
 * Author:YuanKe
 * Date:2020年1月12日
 */
//应用公共文件
namespace app\common;


class common
{
    /**
     * @param $code
     * @param $message
     * @param array $data
     * @return array
     */
    public static function return_result($code, $message,$data = [])
    {
        $result =[
            'code'          =>  $code,
            'msg'           =>  $message,
            'data'           =>$data,
        ];
        return $result;
    }

//----------------------------------------------------------------------------------------------------------------------

}