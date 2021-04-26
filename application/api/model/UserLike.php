<?php
namespace app\api\model;

use app\api\service\Token as TokenService;
use app\lib\exception\UserException;
use think\Request;

/**
 * Class UserLike
 * @package app\api\model
 */
class UserLike extends BaseModel
{
    /**
     * 获赞数量
     */
    public static  function getLikeByCount($user_id)
    {
        $result = db('user')
            ->where('id',$user_id)
            ->field('like_count')
            ->find();
        return $result;
    }

    /**
     * 点赞用户短视频
     * @param $id
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function addListCount($user_id)
    {
        $love =intval(Request::instance()->post('love'));
        $to_user_id=Request::instance()->post('to_user_id');
        $video_id = Request::instance()->post('video_id');
        $result =db('user_like')
            ->alias('like')
            ->where('like.video_id',$video_id)
            ->where('like.user_id',$user_id)
            ->find();
        //判断用户是否存在
        $toUserId = User::get($to_user_id);
        if (!$toUserId){
            throw new UserException();
        }
        //赞过别人不能重复点赞
        if($love == 0 ) {
            if ($result) {
                return json_encode(["status"=>0,"msg"=>"你已经赞过了!"],JSON_UNESCAPED_UNICODE);
            }

            $count= [
                'user_id'=>$user_id,
                'to_user_id'=>$to_user_id,
                'video_id'=>$video_id,
                'love'=>1,
                'create_time'=>time(),
            ];


            //插入点赞表
            db('user_like')->insert($count);
            //给用户所有视频获赞(用户表)
            db('user')
                ->where('id',$to_user_id)
                ->setInc('like_count',1); //+1
            //给短视频表 点赞数
            db('videos')
                ->where('id',$video_id)
                ->setInc('like_counts',1); //+1
            return  json_encode(["status"=>1,"msg"=>"点赞成功!"],JSON_UNESCAPED_UNICODE);
        }elseif ($love == 1){
            //删除点赞id
            db('user_like')
                ->where('user_id',$user_id)
                ->where('to_user_id',$to_user_id)
                ->where('video_id',$video_id)
                ->delete();

            db('user')
                ->where('id',$to_user_id)
                ->where('like_count>0')
                ->setDec('like_count',1); //-1
            db('videos')
                ->where('id',$video_id)
                ->where('like_counts>0')
                ->setDec('like_counts',1); //-1
            return json_encode(["status"=>0,"msg"=>"取消点赞!"],JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 查看赞过用户视频列表
     */
    public static function myAddListCount($id)
    {
        $result = db('user_like')
            ->alias('like')
            ->where('like.user_id',$id)
            ->join('__USER_ADDRESS__ address','address.user_id=like.to_user_id')
            ->join('__VIDEOS__ video','like.video_id = video.id')
            ->field('address.nickname,address.avatar_url,
            video.id,title,video_url,video_path,num_count,like_counts,like.love,to_user_id')
            ->select();
        return $result;
    }

    /**
     * 统计赞过总数
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
   public static function myThumbsUpCount($id)
   {
       $result =db('user_like')
           ->where('user_id',$id)
           ->count('video_id');
       return $result;
   }

}