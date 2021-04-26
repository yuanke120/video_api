<?php
namespace app\api\service;

use app\api\model\User as UserModel;
use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WeiChatException;
use think\Exception;
use think\Request;

class UserToken extends  Token
{
    protected $code;
    protected $wxLoginUrl;
    protected $wxAppId;
    protected $wxAppSecret;

    public function __construct($code)
    {
        $this->code=$code;
        $this->wxAppId=config('wx.app_id');
        $this->wxAppSecret=config('wx.app_secret');
        $this->wxLoginUrl=sprintf(config('wx.login_url'),$this->wxAppId,$this->wxAppSecret,$this->code);
    }

    /**
     *小程序登录校验
     * @return string
     * @throws Exception
     * @throws TokenException
     * @throws WeiChatException
     */
    public function get()
    {
        $result=curl_get($this->wxLoginUrl);
        $wxResult=json_decode($result,true);
        if(empty($wxResult)){
            throw new Exception('获取openid失败，微信接口错误');
        }else{
            $loginFail=array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);//拿到token
            }
        }
    }

    //调用微信登录失败异常
    private function processLoginError($wxResult)
    {
        throw new WeiChatException([
            'msg'=>$wxResult['errmsg'],
            'errorCode'=>$wxResult['errcode'],
        ]);
    }

    /**
     * 拿到2个openid和session_key并设置7200s等于2小时
     * @param $wxResult
     * @return string
     * @throws TokenException
     */
    private function  grantToken($wxResult)
    {
        $openId=$wxResult['openid'];
        $user=UserModel::getByOpenId($openId);
        if (!$user){
            $uid=$this->newUser($openId);
        }else{
            $uid=$user->id;
        }
        $cacheValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cacheValue);
        return $token;
    }

    //创建新用户 存储放在模型数据库里
    private function newUser($openid)
    {
        $nickname =Request::instance()->post('nickname');
        $avatar_url =Request::instance()->post('avatar_url');
        //User表
       $data=[
            'openid'=>$openid,
            'nickname'=>$nickname,
            'avatar_url'=>$avatar_url,
        ];
        $user=UserModel::create($data);

        //Address表
        $user_id = $user->id;
        $name = $user->nickname;
        $avatar = $user->avatar_url;

        $address =[
            'user_id' =>$user_id ,
            'nickname'=>$name,
            'avatar_url'=>$avatar,
            'create_time'=>time(),
        ];

        db('user_address')->insert($address);

        return $user->id;
    }

    //不让别人拿到你的令牌伪造token
    //写入缓存
    private function saveToCache($wxResult)
    {
        $key=self::generateToken();//生成token
        $value = json_encode($wxResult);
        $expire_in=config('setting.token_expire_in');
        $result = cache($key, $value, $expire_in);
        if(!$result){
            throw new TokenException([
                'msg'=>'服务器缓存异常',
                'errorCode'=>10005,
            ]);
        }
        return $key;
    }

    /**
     * 组装缓存数据
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCachedValue($wxResult,$uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid']=$uid;
        $cacheValue['scope']=ScopeEnum::User;
        return $cacheValue;
    }


}