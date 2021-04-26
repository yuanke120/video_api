<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\UserFans;
use app\api\service\Token as TokenService;
use app\lib\exception\UserException;

class Fans extends BaseController
{
    /**
     * 查看粉丝列表
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function myFansList()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserFans::UserFansList($uid);
        return $result;
    }

    /**
     * 我的粉丝数量
     * @throws UserException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function fansTotal()
    {
        $uid = TokenService::getCurrentUid();
        $user_fans = UserFans::getByFansCount($uid);
        return $user_fans;
    }

    /**
     * (互相关注）粉丝
     * @return string
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function addFansCount()
    {
        $uid =TokenService::getCurrentUid();
        $result =UserFans::addFans($uid);
        return $result;
    }

    /**
     * 粉丝取消互相关注
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function deleteFans()
    {
        $uid = TokenService::getCurrentUid();
        $userId = UserFans::delFans($uid);
        if(!$userId){
            return json_encode(array("status" => 0, "msg" => "取消互相关注失败"), JSON_UNESCAPED_UNICODE);
        }else {
            return json_encode(array("status"=>0,"msg"=>"取消互相关注成功"),JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 关注用户粉丝数量
     * @return int|mixed|string
     * @throws \think\Exception
     */
    public function otherFansCount()
    {
        $uid = TokenService::getCurrentUid();
        $user_id = UserFans::otherFansCount($uid);
        return $user_id;
    }

    /**
     * 查看用户粉丝获赞数量
     * @return mixed
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function otherLikeCount()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserFans::otherLike($uid);
        return  $result;
    }
}