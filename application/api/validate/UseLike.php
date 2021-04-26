<?php
namespace app\api\validate;

/**
 * 点赞效验
 * Class UseLike
 * @package app\api\validate
 */
class UseLike extends BaseValidate
{
    protected $rule=[
        'user_id'      =>'require|isNotEmpty',  //不允许为空
        'to_user_id'  =>'require|isNotEmpty',
        'video_id'      =>'require|isNotEmpty'
    ];
}