<?php
namespace app\api\controller\v1;

use app\api\model\Videos;
use app\api\model\VideoTag;
use app\lib\exception\TokenException;
use app\lib\exception\VideoTagsException;
use think\Controller;
use think\Exception;
use app\api\service\Token as TokenService;
use think\Request;

/**
 * Class VideoTags
 * @package app\api\controller\v1
 */
class VideoTags extends Controller
{
    /**
     * 话题列表
     * @return mixed
     * @throws VideoTagsException
     */
    public function getTagAll()
    {
        $result = VideoTag::getVideoTagList();
        if(!$result){
            throw new  VideoTagsException();
        }
        return $result;
    }

    /**
     * 话题详情
     * @param $id
     * @return mixed
     * @throws Exception
     */
    public function getTagById()
    {
        $tag_id = VideoTag::getVideoTagById();
        if(!$tag_id){
            throw new Exception([
                'msg'=>"话题不存在",
                'errorCode'=>60001
            ]);
        }
        return $tag_id;
    }

    /**
     * 上传短视频加话题
     * @return false|\think\Model
     * @throws Exception
     * @throws TokenException
     */
    public function addVideoTag()
    {
        $uid=TokenService::getCurrentUid();
        $userId=new VideoTag();
        $result = $userId->addTagById($uid);
        return $result;

    }
}