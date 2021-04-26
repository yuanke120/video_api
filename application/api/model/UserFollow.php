<?php
namespace app\api\model;

use think\Request;

class UserFollow extends BaseModel
{
    /**
     * 我的关注数量
     */
    public static  function getByFollowCount($user_id)
    {
        $result = db('user')
            ->where('id',$user_id)
            ->field('follow_count')
            ->find();
        return $result;
    }

    /**
     * 关注用户id
     * @param $user_id
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function addFollow($user_id)
    {
        $love = intval(Request::instance()->post('love'));
        $to_user_id = Request::instance()->post('to_user_id');
        $result = db('user_follow')
            ->alias('follow')
            ->where('follow.user_id', $user_id)
            ->where('follow.to_user_id', $to_user_id)
            ->find();
        if($user_id == $to_user_id){
            return  json_encode(["status"=>0,"msg"=>"你不能关注自己"],JSON_UNESCAPED_UNICODE);
        }

        if($love==0){
            if($result){
                return json_encode(["status" => 0, "msg" => "已关注了Ta！"],JSON_UNESCAPED_UNICODE);
            }
            $count= [
                'user_id'=>$user_id,
                'to_user_id'=>$to_user_id,
                'love'=>1,
                'create_time'=>time(),
            ];
            //插入关注表
            $res = db('user_follow')->insert($count);
            if ($res){
            db('user_follow')
                ->where('user_id',$to_user_id)
                ->where('to_user_id',$user_id)
                ->setField('love',1);

            db('user')->where('id', $user_id)->setInc('follow_count', 1); //+1
                return json_encode(["status" => 1, "msg" => "成功关注Ta!!"], JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(["status" => 0, "msg" => "失败成功关注Ta!!"], JSON_UNESCAPED_UNICODE);
            }
        }elseif ($love == 1) {
                $res = db('user_follow')
                    ->where('user_id', $user_id)
                    ->delete();
                if ($res) {
                    db('user_follow')
                        ->where('user_id', $to_user_id)
                        ->setField('love', 1);
                    db('user')
                        ->where('id', $user_id)
                        ->where('follow_count>0')
                        ->setDec('follow_count', 1); //-1
                    return json_encode(["status" => 1, "msg" => "取消关注Ta!!"], JSON_UNESCAPED_UNICODE);
                }else{
                    return json_encode(["status" => 0, "msg" => "失败取消关注Ta!"], JSON_UNESCAPED_UNICODE);
                }
            }
    }

    /**
     * 关注列表
     */
    public static function followByList($uid)
    {
        $result =db('user_follow')
            ->alias('follow')
            ->where('follow.to_user_id',$uid)
            ->join('__USER_ADDRESS__ address','address.user_id = follow.user_id')
            ->field('follow.user_id,address.nickname,avatar_url,follow.love as follow_love')
            ->select();
        return $result;
    }

    /**
     * 查看关注用户的视频列表
     */
    public static function followByVideoList($id)
    {
      $ressult = db('user_follow')
          ->alias('follow')
          ->where('follow.user_id',$id)
          ->join('__VIDEOS__ video','video.user_id = follow.to_user_id')
          ->join('__USER_ADDRESS__ address','video.user_id = address.user_id')
          ->select();
      return $ressult;
    }

    /**
     * 查看的粉丝的的用户的所有视频列表首页
     * @param $uid
     * @return mixed
     * @throws \think\Exception
     */
    public static function followUserVideoList($uid)
    {
        $result = db('user_follow')
            ->alias('follow')
            ->where('follow.user_id',$uid)
            ->join('__VIDEOS__ video','video.user_id = follow.to_user_id')
            ->join('__USER_ADDRESS__ address','address.user_id = follow.to_user_id')
            ->field('follow.to_user_id,address.nickname,avatar_url,
            video.id as video_id,video.title,video_url,video_path,num_count,like_counts,video.create_time')
            ->select();
        db('videos')->where('user_id',$uid)->count('id');
        return $result;
    }

    /**
     * 取消关注用户
     * @param $id
     * @return mixed
     * @throws \think\Exception
     */
    public static  function deleteFollow($id)
    {
        $to_user_id = Request::instance()->post('to_user_id');
        $result =db('user_follow')->where('user_id',$id)->where('to_user_id',$to_user_id)->delete();
        db('user')->where('id',$id)->where('follow_count>0')->setDec('follow_count',1); //-1
        return $result;
    }
}