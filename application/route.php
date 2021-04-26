<?php
use think\Route;

//------登录相关接口------
Route::post('api/:version/token/user', 'api/:version.Token/getToken');  //用户登录   1
Route::post('api/:version/token/app', 'api/:version.Token/getAppToken'); //用户权限  1
Route::post('api/:version/token/verify', 'api/:version.Token/verifyToken'); //测试   1

//------用户个人资料-------
Route::get('api/:version/address', 'api/:version.Address/getUserAddress'); //获取用户个人资料信息 1
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress'); //修改/更新用户信息 1
Route::post('api/:version/address/say', 'api/:version.Address/editByAddress',[],['id'=>'\d+']); //修改发布个人介绍  1
Route::post('api/:version/images/upload','api/:version.Image/upload'); //上传和保存头像  1

//------视频相关接口-------
Route::get('api/:version/video/all','api/:version.Video/videoAll'); //推荐短视频列表  1
Route::get('api/:version/video/zan','api/:version.Video/zan'); //点赞过短视频列表api  1
Route::get('api/:version/video/:id','api/:version.Video/getOneById',[],['id'=>'\d+']); //短视频id详情  1
Route::get('api/:version/video/del/:id','api/:version.Video/deleteOne',[],['id'=>'\d+']); //短视频id删除  1
Route::get('api/:version/video/setinc','api/:version.Video/videoSetInc');//视频列表滑动自增  1

//------我的粉丝/关注/获赞接口------
Route::get('api/:version/user/like','api/:version.Like/likeOfCount');//获赞数量  1
Route::get('api/:version/user/focus','api/:version.Follow/focusTotal');//关注数量 1
Route::get('api/:version/user/fans','api/:version.Fans/fansTotal');//我的粉丝数量  1

//------用户相关接口------
Route::get('api/:version/user/my','api/:version.Video/userVideo');//我的作品短视频列表  1
Route::get('api/:version/video/count','api/:version.Video/videoCount'); //我的作品视频数量  1

//------点赞用户接口-------
Route::get('api/:version/like/count','api/:version.Like/myThumbs');//我赞过用户视频数量  1
Route::get('api/:version/my/like','api/:version.Like/myZan'); //查看我赞过用户视频列表 1
Route::post('api/:version/like','api/:version.Like/myThumbsUp'); //我点赞用户视频状态 1

//------关注用户接口-------
Route::get('api/:version/user/focus/list','api/:version.Follow/myFollowList');//我关注用户 关注列表 1
Route::post('api/:version/user/focus/all','api/:version.Follow/addFollowCount');//我关注用户 1
Route::post('api/:version/user/focus/del','api/:version.Follow/deleteFollow');//取消关注用户  1
Route::get('api/:version/order/video','api/:version.Follow/userFollowVideoList');//查看的粉丝的的用户的所有视频列表首页 1
Route::get('api/:version/other/like','api/:version.Fans/otherLikeCount'); //查看的(关注/粉丝的的)用户获赞数量  1
Route::get('api/:version/other/fans','api/:version.Fans/otherFansCount');//查看的(关注/粉丝的的)用户粉丝用户数量 1
Route::get('api/:version/user/focus','api/:version.Follow/focusTotal');//关注用户数量  1

//------互相粉丝用户接口-------
Route::get('api/:version/user/fans/list','api/:version.Fans/myFansList'); //粉丝的用户关注我 粉丝列表 1
Route::post('api/:version/user/fans/all','api/:version.Fans/addFansCount');//互相关注粉丝  1
Route::post('api/:version/user/fans/del','api/:version.Fans/deleteFans');//粉丝取消互相关注  1

//------话题相关接口-------
Route::get('api/:version/tags/all','api/:version.VideoTags/getTagAll');//话题列表  1
Route::get('api/:version/tags/:id','api/:version.VideoTags/getTagById'); //话题详情  1
Route::post('api/:version/tags/add','api/:version.VideoTags/addVideoTag');//添加话题  1
Route::get('api/:version/tags/search','api/:version.Search/getBySearch'); //搜索话题  线上搜索模糊有问题 本地搜索模糊没问题

//------长测评相关接口------
Route::post('api/:version/info','api/:version.Comment/info'); //给视频评论,提交评论  1
Route::get('api/:version/comment/count','api/:version.Comment/count'); //视频评论总数  1
Route::get('api/:version/comment/list','api/:version.Comment/commentList'); //视频相关评论点赞列表  1
Route::post('api/:version/comment/like','api/:version.Comment/contentLike'); //视频用户评论点赞  1

//------上传短视频视频相关接口-------
Route::post('api/:version/video/upload','api/:version.ImageVideo/upload'); //上传调用接口 1

//------消息相关接口------
Route::get ('api/:version/message/info','api/:version.Message/comment');  //消息通知
