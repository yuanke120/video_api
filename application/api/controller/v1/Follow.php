<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\UserFollow;
use app\api\service\Token as TokenService;
use think\Exception;

class Follow extends BaseController
{
    /**
     * 我的关注数量
     * @return mixed
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public function focusTotal()
    {
        $uid = TokenService::getCurrentUid();
        $userFollow=UserFollow::getByFollowCount($uid);
        return $userFollow;
    }

    /**
     * 关注用户id
     * @return string
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function addFollowCount()
    {
        $uid=TokenService::getCurrentUid();
        $result=UserFollow::addFollow($uid);
        return $result;
    }

    /**
     * 关注列表
     * @return mixed
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function myFollowList()
    {
        $uid=TokenService::getCurrentUid();
        $follow_id =UserFollow::followByList($uid);
        return $follow_id;
    }

    /**
     * 查看的粉丝的的用户的所有视频列表首页
     * @return mixed
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public  function followVideoList()
    {
        $uid = TokenService::getCurrentUid();
        $follow_id = UserFollow::followByVideoList($uid);
        return $follow_id;
    }

    /**
     * 用户取消关注
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function deleteFollow()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserFollow::deleteFollow($uid);
        if($result){
            return  json_encode(['status'=>1,'msg'=>'您已取消关注'], JSON_UNESCAPED_UNICODE);
        }else{
            return  json_encode(['status'=>0,'msg'=>'取消关注失败'], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 查看的粉丝的的用户的所有视频列表首页
     * @return mixed
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public function  userFollowVideoList()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserFollow::followUserVideoList($uid);
        return $result;
    }

}