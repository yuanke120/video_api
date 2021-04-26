<?php
namespace  app\lib\enum;

/**
 *给每个令牌划分一个scope作用域,每个作用域
 *可访问多个接口
 * Class ScopeEnum
 * @package app\lib\enum
 */
class ScopeEnum
{
    //微信用户的权限
    const  User = 16;
    //管理员后台的权限
    const Super = 32;
}