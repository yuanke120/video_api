<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use app\api\model\Comment as CommentModel;
use app\api\model\User;
use app\lib\exception\TokenException;

/**
 * 用户点赞评论
 * Class Comment
 * @package app\api\controller\v1
 */
class Comment extends BaseController
{
    /**
     * 给视频评论,提交评论
     * @return int|string
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function info()
    {
        $uid = TokenService::getCurrentUid();
        $result =CommentModel::myInfo($uid);
        return $result;
    }

    /**
     * 视频详情id评论总数
     * @return int|string
     * @throws TokenException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function count()
    {
        $result = CommentModel::contentCont();
        return $result;
    }

    /**
     * 用户评论点赞Comment
     * @throws TokenException
     * @throws \think\Exception
     */
    public function contentLike()
    {
        $uid =TokenService::getCurrentUid();
        $result =CommentModel::addThumbs($uid);
        return $result;
    }


    /**
     * 视频相关评论点赞列表
     * @throws TokenException
     * @throws \think\Exception
     */
    public function commentList()
    {
        $uid = TokenService::getCurrentUid();
        $user= User::get($uid);
        if(!$user){
            throw new TokenException();
        }
        $comment_id = CommentModel::CommentThumbsList();
        return $comment_id;
    }
}