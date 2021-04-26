<?php
namespace app\api\validate;

class IDCollection extends BaseValidate
{
    protected $rule=[
        'ids' =>'require|checkIDs',
    ];
    protected $message=[
        'ids'=>'ids参数必须都好分割多个正整数',
    ];
}