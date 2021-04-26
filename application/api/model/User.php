<?php
namespace app\api\model;

class User extends BaseModel
{
    public function  address()
    {
        $result=$this->hasOne('UserAddress','user_id','id');
        return $result;
    }

    /**
     *用户是否存在
     * 存在返回uid，不存在返回0
     * @param $openId
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getByOpenId($openId)
    {
        $user = User::where('openid', '=', $openId)
            ->find();
        return $user;
    }


}