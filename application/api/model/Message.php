<?php
namespace app\api\model;

use think\Request;

class Message extends  BaseModel
{
    /**
     * 消息接口
     * @param $user_id
     * @return string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static  function myInfo($user_id)
    {
        $to_user_id = Request::instance()->post('to_user_id');
        $comment_id = Request::instance()->post('comment_id');
        $like_id = Request::instance()->post('like_id');
        $comment_love_id = Request::instance()->post('comment_love_id');
        $video_id = Request::instance()->post('video_id');
        $fans_id = Request::instance()->post('fans_id');
        $love =intval(Request::instance()->post('status_read'));
        //查看是否已读
        $result =db('message')
            ->where('status_read','=','0')
            ->where('user_id',$user_id)
            ->find();

        if($love == 0) {
            if ($result) {
                return json_encode(["status" => 0, "msg" => "消息已读了!!!"], JSON_UNESCAPED_UNICODE);
            }
            //插入消息通知表
            $message = [
                'comment_id' => $comment_id,
                'video_id' => $video_id,
                'fans_id' => $fans_id,
                'to_user_id' => intval($to_user_id),
                'status_read' =>1,
                'like_id'=>$like_id,
                'comment_love_id'=>$comment_love_id,
                'create_time' => time()
            ];
            //插入消息通知
            db('message')->insert($message);
            //打开通知
            $result = db('message')
                ->alias('m')
                ->join('__COMMENT__ c','c.id = m.comment_id')
                ->join('__COMMENT_LIKE__ cl','c.id = cl.comment_id')
                ->join('__USER_LIKE__ like','like.user_id = m.user_id')
                ->join('__USER_FANS__ fans','fans.id = m.fans_id')
                ->join('__USER_ADDRESS__ address','address.user_id =m.user_id')
                ->field('c.video_id,comment,cl.love as comment_love,fans.to_user_id as fans_love,
                like.love as zan_love,address.nickname,avatar_url,m.to_user_id')
                ->select();
            return  $result;
        }elseif ($love == 1){
            db('message')
                ->where('user_id',$user_id)
                ->delete();
            return  json_encode(["status"=>0,"msg"=>"通知没更新!"], JSON_UNESCAPED_UNICODE);
        }
    }

}