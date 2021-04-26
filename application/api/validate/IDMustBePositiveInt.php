<?php
namespace app\api\validate;

use app\api\validate\BaseValidate as BaseValidate;

/**
 * Class IDMustBePositiveInt
 * @package app\api\validate
 */
class IDMustBePositiveInt extends BaseValidate
{
    protected $rule=[
        'id'=>'require|isPositiveInteger'
    ];
    protected $message=[
        'id'=>'id必须是正整数'
    ];

}