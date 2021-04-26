<?php
namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
    protected $dateFormat='Y-m-d';
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    protected $hidden=['id','delete_time','create_time','update_time'];

    public function prefixImgUrl($value,$data)
    {
        $url = $value;
        if($data['from'] == 1){
            $url = config('setting.img_prefix').$value;
        }
        return $url;
    }
}