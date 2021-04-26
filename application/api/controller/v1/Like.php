<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\UserLike;
use app\api\service\Token as TokenService;
use app\lib\exception\TokenException;
use app\lib\exception\UserException;
use think\Exception;
use think\Request;


/**
 * Class Like
 * @package app\api\controller\v1
 */
class Like extends BaseController
{
    /**
     * 获赞数量
     * @return mixed
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public function likeOfCount()
    {
        $uid = TokenService::getCurrentUid();
        $likeCount =UserLike::getLikeByCount($uid);
        return $likeCount;
    }

    /**
     * 赞过用户视频
     * @return mixed
     * @throws UserException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function myThumbsUp()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserLike::addListCount($uid);
        return $result;
    }

    /**
     * 查看赞过用户视频列表
     * @return mixed
     * @throws TokenException
     * @throws \think\Exception
     */
    public function myZan()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserLike::myAddListCount($uid);
        return $result;
    }

    /**
     * 统计赞过总数
     * @return int|string
     * @throws TokenException
     * @throws \think\Exception
     */
    public function myThumbs()
    {
        $uid = TokenService::getCurrentUid();
        $user_id = UserLike::myThumbsUpCount($uid);
        return $user_id;
    }

    /**
     * 取消点赞
     * @return mixed
     * @throws TokenException
     * @throws \think\Exception
     */
    public function thumbs()
    {
        $uid = TokenService::getCurrentUid();
        $result = UserLike::zanDel($uid);
        return $result;
    }

}