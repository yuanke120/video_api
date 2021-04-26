<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\service\Token as TokenService;
use think\Request;

class Image extends  Controller
{
    /**
     * 上传微信头像图片文件
     * @return \think\response\Json
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function upload()
    {
        $uid =TokenService::getCurrentUid();
        if ($this->request->isPost()) {
            $res['code'] = 0;
            $res['msg'] = "上传成功";
            $file = $this->request->file('file');
            $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,BMP,JPEG'])->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                $res['data']['title'] = $info->getFilename();
                $filepath = $info->getSaveName();
                $res['data']['src'] = "/uploads/".$filepath;
                $list = db('user_address')
                    ->where('user_id',$uid)
                    ->update(['avatar_url' => $res['data']['src']]);
                if (!$list){
                    $res['code'] = 1;
                    $res['msg'] = '上传失败';
                }
            } else {
                $res['code'] = 1;
                $res['msg'] = '上传失败' . $file->getError();
            }
            return json($res);
        }
    }
}
