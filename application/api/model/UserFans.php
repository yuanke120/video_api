<?php
namespace app\api\model;

use think\Request;

/**
 * Class UserFans
 * @package app\api\model
 */
class UserFans extends BaseModel
{
    /**
     * 查看粉丝列表
     */
    public static function UserFansList($uid)
    {
        $result = db('user_fans')
            ->alias('fans')
            ->where('fans.to_user_id',$uid)
            ->join('__USER_ADDRESS__ address','address.user_id = fans.user_id')
            ->field('address.nickname,fans.user_id')
            ->select();
        return $result;
    }

    /**
     * 我的粉丝数量
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
    public static function getByFansCount($id)
    {
        $result = db('user')
            ->where('id',$id)
            ->field('fans_count')
            ->find();
        return $result;
    }

    /**
     * 互相关注粉丝
     * @param $user_id
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function addFans($user_id)
    {
        $love = intval(Request::instance()->post('love'));
        $to_user_id = intval(Request::instance()->post('to_user_id'));
        $result = db('user_fans')
            ->alias('fans')
            ->where('fans.user_id', $user_id)
            ->where('fans.to_user_id', $to_user_id)
            ->find();
        if($user_id == $to_user_id){
            return  json_encode(["status"=>0,"msg"=>"你不能关注自己"],JSON_UNESCAPED_UNICODE);
        }
        if($love == 0){
            $count = [
            'user_id' => $user_id,
            'to_user_id' => $to_user_id,
            'love' => 1,
            'create_time' => time(),
        ];
            if($result) {
                return json_encode(["status" => 0, "msg" => "已互关注了！"], JSON_UNESCAPED_UNICODE);
            }
            //插入粉丝表
            $res = db('user_fans')->insert($count);
            //给用户粉丝(用户表)
            if($res){
                db('user_fans')
                    ->where('user_id',$to_user_id)
                    ->where('to_user_id',$user_id)
                    ->setField('love',1);

                db('user')->where('id', $to_user_id)->setInc('fans_count', 1); //+1
                return json_encode(["status" => 1, "msg" => "互相关注成功!!"], JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(["status" =>0, "msg" => "失败互相关注!!"], JSON_UNESCAPED_UNICODE);
            }
        }elseif ($love == 1) {
            //删除关注等于取消互相关注粉丝id
            $res = db('user_fans')
                ->where('user_id', $user_id)
                ->where('to_user_id',$to_user_id)
                ->delete();
            if($res){
                db('user_fans')
                    ->where('user_id',$to_user_id)
                    ->where('to_user_id',$user_id)
                    ->setField('love',0);

                db('user')->where('id', $to_user_id)->where('fans_count>0')->setDec('fans_count', 1); //-1
                return json_encode(["status" => 1, "msg" => "取消互相关注成功!"], JSON_UNESCAPED_UNICODE);
            }else{
                return json_encode(["status" => 0, "msg" => "失败取消互相关注!"], JSON_UNESCAPED_UNICODE);
            }
        }
    }

    /**
     * 粉丝取消互相关注
     * @param $id
     * @return mixed
     * @throws \think\Exception
     */
    public  static  function  delFans($id)
    {
        $to_user_id = Request::instance()->post('to_user_id');
        $result =db('user_fans')->where('user_id',$id)->where('to_user_id',$to_user_id)->delete();
        //用户表
        db('user')->where('id',$id)->where('fans_count>0')->setDec('fans_count',1); //-1
        return $result;
    }

    /**
     * 查看用户粉丝数量
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
    public static function  otherFansCount($id)
    {
        $result = db('user_fans')
            ->alias('fans')
            ->where('fans.to_user_id',$id)
            ->join('__USER__ user','user.id  = fans.user_id')
            ->join('__USER_ADDRESS__ address','address.user_id = user.id')
            ->field('address.nickname,user.fans_count')
            ->count();
        return $result;
    }

    /**
     * 查看关注用户粉丝获赞数量
     */
    public static function otherLike($id)
    {
        $result =db('user_like')
            ->alias('like')
            ->where('like.to_user_id',$id)
            ->join('__USER__ user','user.id  = like.user_id')
            ->join('__USER_ADDRESS__ address','address.user_id = user.id')
            ->field('address.nickname,user.like_count')
            ->find();
        return $result;
    }

}