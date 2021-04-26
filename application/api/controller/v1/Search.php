<?php
namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\VideoTag;

class Search extends BaseController
{
    /**
     *搜索话题
     */
    public function getBySearch()
    {
        $tags=VideoTag::getByVideoTags();
        return $tags;
    }
}
