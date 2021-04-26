<?php
namespace app\api\controller\v1;

use app\api\model\User;
use app\api\model\User as UserModel;
use app\api\model\Videos;
use app\lib\exception\UserException;
use app\lib\exception\VideoException;
use app\lib\exception\VideoTagsException;
use think\Controller;
use think\Exception;
use app\api\service\Token as TokenService;

class Video extends Controller
{
    /**
     * 视频列表
     * @return mixed
     * @throws Exception
     * @throws VideoException
     */
    public function videoAll()
    {
        $uid = TokenService::getCurrentUid();
        $user=UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        $videos = Videos::videoList();
        return $videos;
    }

    /**
     * 点赞过视频的列表api
     * @return mixed
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public function zan()
    {
        $uid = TokenService::getCurrentUid();
        $videos = Videos::userVideoThumbsUp($uid);
        return $videos;
    }

    /**
     * 视频详情id
     * @url /video/:id
     * @param $id
     * @return mixed
     * @throws VideoException
     * @throws \app\lib\exception\ParameterException
     * @throws \think\Exception
     */
    public function getOneById($id)
    {
        $video = Videos::getVideoDetail($id);
        if (!$video) {
            throw new VideoException();
        }
        return $video;
    }

    /**
     * 删除短视频id
     * @return Exception
     * @throws Exception
     * @throws VideoTagsException
     * @throws \app\lib\exception\TokenException
     * @throws \think\exception\PDOException
     */
    public function deleteOne()
    {
        $uid=TokenService::getCurrentUid();
        $del = Videos::del($uid);
        if (!$del) {
            throw new VideoTagsException([
                'msg' => "短视频删除失败",
                'errorCode' => 60001
            ]);
        }
        return json_encode(['code'=>200,'msg'=>"删除短视频成功"],JSON_UNESCAPED_UNICODE);
    }

    /**
     * 查看我的作品短视频列表
     * @return mixed
     * @throws Exception
     * @throws UserException
     * @throws \app\lib\exception\TokenException
     */
    public function userVideo()
    {
        $uid = TokenService::getCurrentUid();
        $myVideo = Videos::getMyVideoById($uid);
        if(!$myVideo){
            throw new UserException([
                'code'=>401,
                'msg'=>'我的作品不存在',
                'errorCode'=>10000
            ]);
        }
        return $myVideo;
    }

    /**
     * 点赞视频id
     * @throws Exception
     * @throws UserException
     * @throws \app\lib\exception\TokenException
     * @throws \think\exception\DbException
     */
    public function addLikeVideo()
    {
        $uid = TokenService::getCurrentUid();
        $userId = User::get($uid);
        if (!$userId) {
            throw  new UserException([
                'msg' => '你没登录，不能给任何人点赞',
                'errorCode' => 60001,
            ]);
        }
    }

    /**
     * 我的发布的视频总数
     * @return float|int
     * @throws Exception
     * @throws \app\lib\exception\TokenException
     */
    public function videoCount()
    {
        $uid = TokenService::getCurrentUid();
        $video_id = Videos::myVideoCount($uid);
        return $video_id;
    }

    /**
     * 自增视频次数
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function videoSetInc()
    {
        $user_id = Videos::VideoSetInc();
        return $user_id;
    }

}