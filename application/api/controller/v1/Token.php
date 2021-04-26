<?php
namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\UserAddress;
use app\api\model\UserFans;
use app\api\model\UserFollow;
use app\api\model\UserLike;
use app\api\model\Videos;
use app\api\service\AppToken;
use app\api\service\UserToken;
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;
use app\lib\exception\UserException;
use app\lib\exception\VideoTagsException;
use think\Exception;

class Token
{
    /**
     * 用户获取令牌
     * @url /token
     * @POST code
     * @param string $code
     * @return array
     * @throws Exception
     * @throws ParameterException
     */
    public function getToken($code='')
    {
        (new TokenGet())->goCheck();
        $wx=new UserToken($code);
        $token=$wx->get();
        return ['token'=>$token];
    }
    /**
     * 第三方应用获取令牌
     * @param string $ac
     * @param string $se
     * @return array
     * @throws ParameterException
     * @throws \app\lib\exception\TokenException
     */
    public function getAppToken($ac='',$se='')
    {
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac,$se);
        return [
            'token'=>$token,
        ];
    }

    /**
     * @param string $token
     * @return array
     * @throws ParameterException
     */
    public function verifyToken($token='')
    {
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return ['isValid' => $valid];
    }

}