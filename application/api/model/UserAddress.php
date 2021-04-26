<?php
namespace app\api\model;

use think\Request;


class UserAddress extends BaseModel
{
    /**
     * 修改说说
     */
    public static  function editById($id)
    {
        $result = db('user_address')
            ->alias('address')
            ->where('address.user_id',$id)
            ->find();
        return $result;
    }

    /**
     * 微信头像保存数据库里
     * @param $user_id
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function myAvaTar($user_id)
    {
        $avatar = Request::instance()->post('avatar_url');
        $data = [
            'avatar_url'=>$avatar,
        ];
        $result = db('user_address')
            ->where('avatar_url',$user_id)
            ->update($data);
        return $result;
    }

}