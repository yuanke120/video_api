<?php
namespace app\api\controller\v1;

use think\Controller;
use app\api\service\Token as TokenService;
use app\api\model\Message as MessageModel;

/**
 * 消息通知
 * Class Message
 * @package app\api\controller\v1
 */
class Message extends Controller
{

    /**
     * 用户通知消息
     * @return string
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     */
    public function comment()
    {
        $uid = TokenService::getCurrentUid();
        $result =MessageModel::myInfo($uid);
        return $result;
    }



}