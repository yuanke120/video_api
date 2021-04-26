<?php
namespace app\api\validate;

class AddressNew extends BaseValidate
{
    // 为防止欺骗重写user_id外键
    protected $rule=[
        'nickname'      =>'require|isNotEmpty',  //不允许为空
//        'avatar_url'=>'require',
//        'gender'=>'require',
//        'province'=>'require',
//        'city'=>'require',
//        'say'=>'require'
    ];
}