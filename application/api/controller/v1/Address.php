<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\model\User;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\Token as TokenService;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;
use think\Request;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress,getUserAddress']
    ];

    /**
     * 获取用户个人资料信息
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws UserException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getUserAddress()
    {
        $uid =  TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id',$uid)
            ->find();
        if(!$userAddress){
            throw new UserException([
                'msg' =>'用户个人资料不存在',
                'errorCode'=>60001,
            ]);
        }
        return $userAddress;
    }

    /**
     *更新/创建用户个人资料
     * @return SuccessMessage
     * @throws UserException
     * @throws \app\lib\exception\ParameterException
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\exception\DbException
     */
    public function  createOrUpdateAddress()
    {
        $validate=new AddressNew();
        $validate->goCheck();
        $uid=TokenService::getCurrentUid();
        $user=UserModel::get($uid);
        if(!$user){
            throw new UserException();
        }
        $userAddress = $user->address;
        $data=input('post.');
        $dataArray= $validate->getDataByRule($data);
        if(!$userAddress){
            $user->address()->save($dataArray);
        }else{
            $user->address->save($data);
        }
        return new SuccessMessage();
    }


    /**
     * 发自我介绍说说
     * @return SuccessMessage|array
     * @throws \app\lib\exception\TokenException
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editByAddress()
    {
        $uid = TokenService::getCurrentUid();
        $user=UserModel::get($uid);
        if(!$user){
            throw new UserException([
                'code'=>404,
                'msg'=>'修改失败',
            ]);
        }
        $data=input('post.');
        $user_id = Request::instance()->input('post.');
        $result = UserAddress::editById($user_id);
        if(!$result){
            $user->address->save($data);
        }
        return json_encode(['code'=>200,'msg'=>"修改发布个人介绍成功"],JSON_UNESCAPED_UNICODE);
    }
}
