<?php
namespace app\api\service;

use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    /**
     * 生成token
     * @return string
     */
    public static function generateToken()
    {
        $randChars=getRandChar(32);
        $timestamp=$_SERVER['REQUEST_TIME_FLOAT'];
        $tokenSalt=config('secure.token_salt');
        return md5($randChars.$timestamp.$tokenSalt);
    }

    /**
     * Address
     * 封装token放在header请求头部
     * @param $key
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token); //获取查询token是否存在
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                //判断是否token过期
                $vars= json_decode($vars,true);
                }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }


    /**
     * Address
     * 当需要获取全局uid
     * @return mixed
     * @throws Exception
     * @throws TokenException
     */
    public static function getCurrentUid()
    {
        //token
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * 检查操作是否uid合法
     * @param $checkedId
     * @return bool
     * @throws Exception
     */
    public static function isValidOperate($checkedId)
    {
        if(!$checkedId){
            throw  new Exception('检查uid时必须传入一个被检查uid');
        }
        $currentOperateUid = self::getCurrentUid();
        if($currentOperateUid == $checkedId){
            return true;
        }
        return false;
    }


    /**
     * 校验token是否存在
     * @param $token
     */
    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        $exist ? true  : false ;
    }

    /**
     * 验证token是否合法或者是否过期
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope >= ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    /**
     *只有用户才能访问接口权限
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }

    }

    /**
     * 管理权限
     * @return bool
     * @throws Exception
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function  needSuperScope()
    {
        $scope = self::getCurrentTokenVar(' scope');
        if($scope){
            if($scope == ScopeEnum::Super){
                return true;
            }else{
                throw  new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

}

