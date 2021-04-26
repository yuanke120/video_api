<?php
namespace app\api\model;

use think\Request;


/**
 * Class Comments
 * @package app\api\model
 */
class Comment extends BaseModel
{
    /**
     * 给视频评论,提交评论
     * @param $id
     * @return int|string
     * @throws \think\Exception
     */
    public static function myInfo($id)
    {
        $object_id= Request::instance()->post('object_id');
        $to_user_id = Request::instance()->post('to_user_id');
        $video_id = Request::instance()->post('video_id');
        $comment = Request::instance()->post('comment');
        $data =[
            'object_id'=>$object_id,
            'user_id'=>$id,
            'to_user_id'=>intval($to_user_id),
            'video_id'=>$video_id,
            'comment'=>$comment,
            'create_time' => time()
        ];

        //插入视频表总数
        $res=db('videos')->where('id', $video_id)->setInc('comment_count', 1); //+1
        db('comment')->insert($data);

        if ($res){
            return json_encode(["status"=>1,"msg"=>"评论成功"],JSON_UNESCAPED_UNICODE);
        }else{
            return json_encode(array("status"=>0,"msg"=>"评论失败"),JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     *视频详情id评论总数
     * @param array $param
     * @return int|string
     * @throws \think\Exception
     */
    public static function contentCont()
    {
       $video_id =  Request::instance()->post('id');
       $result = db('videos')
           ->where('id',$video_id)
           ->field('comment_count')
           ->find();
       return $result;
    }

    /**
     * 用户评论点赞
     * @param $user_id
     * @return string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static  function  addThumbs($user_id)
    {
        $love = Request::instance()->post('love');
        $comment_id = Request::instance()->post('comment_id');
        $to_user_id =Request::instance()->post('to_user_id');
        //查看用户是否赞过
        $result = db('comment_like')
            ->where('comment_id',$comment_id)
            ->where('user_id',$user_id)
            ->find();
        if($love == 0){
            if($result){
                return json_encode(["status"=>0,"msg"=>"评论你已经赞过了!"], JSON_UNESCAPED_UNICODE);
            }
            $data = [
                'user_id'       =>$user_id,
                'comment_id'    =>$comment_id,
                'to_user_id'    =>$to_user_id,
                'create_time'   =>time(),
                'love'          =>1
            ];
            //插入评论点赞表
            db('comment_like')->insert($data);
            //同时给用户也获赞
            db('user')->where('id',$to_user_id)->setInc('like_count',1); //+1
            //插入评论表
            db('comment')->where('id',$comment_id)->setInc('like_count', 1);  //+1
            return  json_encode(["status"=>1,"msg"=>"评论点赞成功!"],JSON_UNESCAPED_UNICODE);
        }elseif($love == 1){
            db('comment_like')
                ->where('user_id',$user_id)
                ->where('comment_id',$comment_id)
                ->delete();
            //同时给用户也取消赞
            db('user')->where('id',$to_user_id)->where('like_count>0')->setDec('like_count',1); //-1
            //统计用户喜爱表
            db('comment')->where('id',$comment_id)->where('like_count>0')->setDec('like_count', 1);
            return  json_encode(["status"=>0,"msg"=>"评论取消点赞!"], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * 视频相关评论和点赞列表
     */
    public static function CommentThumbsList()
    {
        $video_id = input('video_id');
        $result = db('comment')
            ->alias('c')
            ->order('c.create_time','desc')
            ->join('__VIDEOS__ video','video.id = c.video_id','LEFT')
            ->join('__USER_ADDRESS__ address','address.user_id =c.user_id','LEFT')
            ->join('__COMMENT_LIKE__ like', 'c.id = like.comment_id','LEFT')
            ->join('__USER_ADDRESS__ a', 'a.user_id = c.to_user_id','LEFT')
            ->field('c.id as comment_id,c.user_id,address.nickname as nickname_my,address.avatar_url as avatar_my,
            c.comment,c.like_count,c.to_user_id,a.nickname as nickname_you,a.avatar_url avatar_you,c.create_time')
            ->where('c.video_id', $video_id)
            ->select();

        //点赞的w k
        $arr =[];  //加个空数组
        foreach($result  as $k=>$v){
            if(empty($v['love'])){
                $v['love']=1;
            }else{
                $v['love']=0;
            }
            if($v['like_count']>=10000){
                $v['like_count']=($v['like_count']/10000);
                $v['like_count']=substr($v['like_count'],0,3)."w+"; //赞数超过才会截获
            }elseif($v['like_count']>=1000){
                $v['like_count']=($v['like_count']/1000);
                $v['like_count']=substr($v['like_count'],0,3)."k+";
            }else{
                $v['like_count'] = $v['like_count'];
            }
            $arr[]=$v;
        }
        return  $arr;
    }

}