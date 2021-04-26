<?php
namespace app\api\model;


use think\Db;
use think\Request;

class VideoTag extends BaseModel
{
    /**
     * 话题列表
     */
    public static function getVideoTagList()
    {
        $result = db('video_tag')
            ->alias('tag')
            ->join('__VIDEOS__ video','tag.id= video.tag_id')
            ->join('__USER_ADDRESS__ address','address.user_id=video.user_id')
            ->field('tag.id,tag_title,images')
            ->select();
        return $result;
    }

    /**
     * 话题详情
     */
    public static function getVideoTagById()
    {
        $id =input('id');
        $result = db('video_tag')
            ->alias('tag')
            ->where('tag.id',$id)
            ->join('__VIDEOS__ video','video.tag_id=tag.id')
            ->join('__USER_ADDRESS__ address','video.user_id =address.user_id')
            ->join('__USER_LIKE__ like','like.user_id = address.user_id','LEFT')
            ->field('address.user_id, address.nickname,avatar_url,video.id,video.title,video_url,video_path,video_s,like_counts,
            like.love as like_love')
            ->select();
        return $result;
    }

    /**
     * 添加话题
     * @param $id
     * @return false|\think\Model
     */
    public function  addTagById($id)
    {
        $tag_id =Request::instance()->post('tag_id');
        $data =['tag_id'=>$tag_id];
        $result = $this->hasOne('video_tag','id')->where('user_id',$id)->save($data);
        return $result;
    }


    /**
     * /search
     * 话题搜索
     */
    public static function getByVideoTags()
    {
        $nickname = input('nickname');
        $title= input('title');
        $map['nickname']=['like','%'.$nickname.'%'];
        $map['title']=['like','%'.$title.'%'];
        $result = db('user_address')
            ->alias('address')
            ->where($map)
            ->join('__VIDEOS__ video','video.user_id = address.user_id')
            ->join('__VIDEO_TAG__ tag','tag.id = video.tag_id')
            ->field('tag.id,tag_title,video.title,video_url,address.nickname,avatar_url')
            ->select();
        return $result;

    }
}