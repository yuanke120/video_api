<?php
/**
 * @author YuanKe
 * @date   2020-08-08
 **/


/**
 * HTTP的头部请求
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */


function curl_get($url,&$httpCode = 0)
{
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //不做证书，部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);

    $file_contents = curl_exec($ch);
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE);//最后一个收到的HTTP代码
    curl_close($ch);
    return $file_contents;
}

/**
 * HTTP的头部请求
 * @param $url
 * @param array $params post请求地址
 * @return mixed
 */
function curl_post($url, array $params = array())
{
    $data_string = json_encode($params);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch, CURLOPT_POST,1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER,
        [
            'Content-Type: application/json'
        ]
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function curl_post_raw($url,$rowData)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,10);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$rowData);
    curl_setopt($ch,CURLOPT_HTTPHEADER,
        [
        'Content-Type: text'
        ]);
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);

}


//32个字符串组
function  getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;
    for($i=0;$i<$length;$i++){
       $str .=$strPol[rand(0,$max)];
    }
    return $str;
}

function fromArrayToModel($m,$array)
{
    foreach($array as $key=>$value){
        $m[$key]=$value;
    }
    return $m;
}

function show($status,$message='',$data=[],$msg='')
{
    return [
        'status'=>intval($status),
        'message'=>$message,
        'data'=>$data,
        'msg'=>$msg
    ];
}
