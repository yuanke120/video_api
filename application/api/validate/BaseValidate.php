<?php
namespace app\api\validate;

use app\lib\exception\ParameterException;
use app\lib\oss\Uploader;
use think\Request;
use think\Validate;

/**
 * Class BaseValidate
 * 基类验证
 */
class BaseValidate extends  Validate
{
    /**
     * 通用校验
     * @return bool
     * @throws ParameterException
     */
    public function goCheck()
    {
        $request=Request::instance();
        $params=$request->param();
        $result=$this->batch()->check($params);
        if(!$result){
            $exception=new ParameterException([
                'msg'=>$this->error,
            ]);
            throw $exception;
        }else{
            return true;
        }

    }
//----------------------------------------------------------------------------------------------------------------------
    /**
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool|string
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value +0) && ($value +0) >0) {
            return true;
        }
        return $field . '必须是正整数';
    }

    /**
     * 检查参数是否为空
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function  isNotEmpty($value,$rule='',$data='',$field='')
    {
        if(empty($value)){
            return $field.'不允许为空';
        }else{
            return true;
        }
    }

    //手机号的验证规则
    protected function isMobile($value)
    {
        $mobile='/^1(3|5|6|7|8)[0-9]\d{8}$/';
        $result=preg_match($mobile,$value);
        return $result ? true :false;
    }

    protected function checkIDs($value)
    {
        $values=explode(',',$value);
        if(empty($values)){
            return false;
        }
        foreach($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }

    /**
     * Address
     * @param $arrays
     * @return array
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

//----------------------------------------------------------------------------------------------------------------------
}