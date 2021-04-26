<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use think\Request;
use app\api\service\Token as TokenService;

class ImageVideo extends BaseController
{
    /**
     * 上传短视频
     * @return string
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function upload()
    {
        $title = Request::instance()->post('title');
        $uid =TokenService::getCurrentUid();
        $file = Request::instance()->file('file');
        $info=$file->move('video');
        $png =md5(date('YmdHis')).".png";
        if ($info && $info->getPathname()){
            $ffmpeg = FFMpeg::create();
            $video_s = $ffmpeg->open($info->getPathname());
            $video_path = $video_s->frame(\FFMpeg\Coordinate\TimeCode::fromSeconds('5'));
//            $video_path->save($png);
            $video_path->save('img'.DS.$png);
            $data =[
                'user_id'=>$uid,
                'video_url'=>trim($info->getPathname(),'"'),
                'title'=>$title,
                'video_path'=>$png,
                'video_s'=>$vtime=exec("ffmpeg -i ".$info->getPathname()." 2>&1 | grep 'Duration' | cut -d ' ' -f 4 | sed s/,//"),//时长
                'create_time'=>time()
            ];
            db('videos')->insert($data);
            return $file->getPathname();
        }else{
            return $file->getError();
        }
    }
}
