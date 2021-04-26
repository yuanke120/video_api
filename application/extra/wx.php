<?php
//  +---------------------------------
//  微信相关配置
//  +---------------------------------
return [
    'app_id'=>'wx839ad809851193fb',

    'app_secret'=>'c74ab283bc164d904e70e5808fbaaa36',

    //weiChat code
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",

    //获取用户资料
    'user_info_url'=>"https://api.weixin.qq.com/cgi-bin/user/info?" .
        "access_token=%s&ACCESS_TOKEN&openid=OPENID&lang=zh_CN，",


    // 微信获取access_token的url地址
    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
        "grant_type=client_credential&appid=%s&secret=%s",
];