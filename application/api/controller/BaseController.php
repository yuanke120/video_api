<?php
namespace app\api\controller;

use app\api\service\Token as TokenService;
use think\Controller;

/**
 * 基类
 * Class BaseController
 * @package app\api\controller
 */
class BaseController extends Controller
{
    // 16用户专有权限
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }

    //验证token是否合法或者是否过期
    protected function  checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkSuperScope()
    {
        TokenService::needSuperScope();
    }

//----------------------------------------------------------------------------------------------------------------------
    const JSON_SUCCESS_STATUS = 1;
    const JSON_ERROR_STATUS = 0;

    /**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderJson($code = self::JSON_SUCCESS_STATUS, $msg = '', $data = [])
    {
        return compact('code', 'msg', 'url', 'data');
    }

    /**
     * 返回操作成功json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderSuccess($data = [], $msg = 'success')
    {
        return $this->renderJson(self::JSON_SUCCESS_STATUS, $msg, $data);
    }

    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderError($msg = 'error', $data = [])
    {
        return $this->renderJson(self::JSON_ERROR_STATUS, $msg, $data);
    }

//----------------------------------------------------------------------------------------------------------------------

}