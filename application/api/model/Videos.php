<?php
namespace app\api\model;

use think\Request;

class Videos extends BaseModel
{
    /**
     * 列表视频
     * @return mixed
     * @throws \think\Exception
     */
    public static  function videoList()
    {
        $result = db('videos')
            ->alias('video')
            ->order('video.create_time','desc')
            ->join('__USER_ADDRESS__ address','address.user_id=video.user_id','LEFT')
            ->join('__VIDEO_TAG__ tag','tag.id = video.tag_id','LEFT')
            ->field('video.id,address.user_id,address.nickname,address.avatar_url,
            video.title,video_url,video_path,video_s,num_count,like_counts,comment_count,video.create_time,
            tag.tag_title')
            ->select();
        return $result;

    }

    /**
     * 点赞过视频列表api

     */
    public static function userVideoThumbsUp($user_id)
    {

        $result = db('videos')
            ->alias('video')
            ->where('like.user_id',$user_id)
            ->order('video.create_time','desc')
            ->join('__USER_ADDRESS__ a','videos.id=a.user_id','left')
            ->join('__USER_LIKE__ like','video.id=like.video_id','left')
            ->join('__USER_ADDRESS__ address','address.user_id=video.user_id','left')
            ->field('video.id,address.user_id,address.nickname,address.avatar_url,video_url,video_path,
            like.love')
            ->select();
            return $result;
        }

    /**
     * 短视频详情并自增
     * @param $id
     * @return mixed
     * @throws \think\Exception
     */
    public static  function getVideoDetail($id)
    {
        $result=db('videos')
            ->alias('video')
            ->where('video.id',$id)
            ->field('video.id,address.nickname,address.avatar_url,video.title,video_url,
            video_path,video.user_id,num_count,like.love as zan,like_counts,comment_count,follow.love as follow_love')
            ->join('__USER__ user','user.id = video.user_id','LEFT')
            ->join('__USER_ADDRESS__ address','address.user_id = user.id','LEFT')
            ->join('__USER_LIKE__ like','like.video_id=video.id','LEFT')
            ->join('__USER_FOLLOW__ follow',' user.id= follow.user_id','LEFT')
            ->find();
        db('videos')->where('id',$id)->setInc('num_count',1);
        return $result;
    }

    /**
     * 删除短视频
     * @param $id
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static  function del($id)
    {
        $result =db('videos')->where('user_id','=',$id)
            ->delete();
        return $result;
    }

    /**
     * 查看我的作品短视频列表
     */
    public static function getMyVideoById($id)
    {
        $result = db('videos')
            ->alias('video')
            ->where('video.user_id',$id)
            ->order('video.create_time','desc')
            ->join('__USER__ user','user.id = video.user_id')
            ->join('__USER_ADDRESS__ address','address.user_id = user.id')
            ->field('address.nickname,address.avatar_url,
            video.user_id,video_url,video_path,num_count,title,like_counts,comment_count,video.id as video_id')
            ->select();
        return $result;
  }

    /**
     * 我的作品总数
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
    public static function myVideoCount($id)
    {
        $result =db('videos')
            ->where('user_id',$id)
            ->count('video_url');
        return $result;
    }

    /**
     * 视频详情自增
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function VideoSetInc()
    {
        $id  =Request::instance()->post('id');
        $result = db('videos')
            ->where('id',$id)
            ->field('num_count')
            ->setInc('num_count',1);
        return $result;
    }
}