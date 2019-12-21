<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/5/27
 * Time: 12:09
 */
namespace common\component\Message;
use common\component\Curl\Curl;
class Sms{

    static public function send_system($send_tel,$send_str){
//        $UserName = "sdk2558";
        $UserName = "NSZ00107";
        $PassWord  = "123456";
        $Sign="每日惠购同城";
        $Host="http://42.96.149.47:1086/sdk/batchsend.aspx";
        $send_str=trim($send_str);
        $send_str.="【".$Sign."】";
        $send_str=mb_convert_encoding($send_str,'GBK','UTF-8');
        $send_str=rawurlencode($send_str);
        $post_fields=array(
            'CorpID'=>$UserName,
            'Pwd'=>$PassWord,
            'Mobile'=>$send_tel,
            'Content'=>$send_str,
            'Cell'=>'',
            'SendTime'=>'',
            'Encode'=>'gb2312'
        );
        $curl=new Curl();
        $str_rec=$curl->post($Host,$post_fields);
        if($str_rec<0){
            return false;
        }else{
            return true;
        }
    }
    static function send_default($telephones,$Message){
        $http = 'http://114.215.206.2/msg/HttpBatchSendSM'; //群发地址
        //  $http = 'http://114.215.206.2/msg/HttpSendSM'; //单发地址
        if(is_array($telephones)){
            $telephone=implode(',',$telephones);
        }else{
            $telephone=$telephones;
        }
        $Message="【每日惠购同城】".$Message;
        $data=[
//            'account'=>'SDK0172',
//            'pswd'=>'365@jiarun',
            'account'=>'NSZ00107',
            'pswd'=>'123456',
            'mobile'=>urlencode($telephone),
            'msg'=>urlencode($Message),
            'needstatus'=>'true',
            'product'=>'',
            'extno'=>'365'
        ];
        // 20151210223904,0
        $curl=new Curl();
        $result= $curl->post($http,$data);
        $arrret = explode(',', $result);
        if ($arrret[1] == 0){
            return true;
        }else{
            return false;
        }
    }
    static function send($telephone,$Message){
        return static::send_default($telephone,$Message);
    }
    static function send_old($telephone,$Message){
        //108 短信发送接口测试
        $uid = '100017'; //用户账号
        $pwd = '258123';
        $http = 'http://115.29.37.184:8860/';//发送地址
        $Message="【每日惠购同城】".$Message;
        $data = array
        (  'cust_code'=>$uid,
            'sp_code'=>'365',
            'content'=>$Message,
            'destMobiles'=>$telephone,
            'sign'=>md5(urlencode($Message).$pwd)
        );
        $curl=new Curl();
        $result= $curl->post($http,$data);
        if(stripos($result,'SUCCESS')===0){
            return true;
        }else{
            return false;
        }
    }
    static public function send_notice($send_tel,$send_str){
//        $UserName = "NSZ2529";
//        $PassWord  = "123456";
        $UserName = "NSZ00107";
        $PassWord  = "123456";
        $Sign="每日惠购同城";
        $Host="http://42.96.149.47:1086/sdk/batchsend.aspx";
        $send_str=trim($send_str);
        $send_str.="【".$Sign."】";
        $send_str=mb_convert_encoding($send_str,'GBK','UTF-8');
        $send_str=rawurlencode($send_str);
        $post_fields=array(
            'CorpID'=>$UserName,
            'Pwd'=>$PassWord,
            'Mobile'=>$send_tel,
            'Content'=>$send_str,
            'Cell'=>'',
            'SendTime'=>'',
            'Encode'=>'gb2312'
        );
        $curl=new Curl();
        $str_rec=$curl->post($Host,$post_fields);
        if($str_rec<0){
            return false;
        }else{
            return true;
        }
    }
    //查询帐户余额
    public static function getBalance(){
        //108 短信发送接口测试
        $uid = '100017'; //用户账号
        $pwd = '258123';
        $http = 'http://115.29.37.184:8860/';//地址
        $data = array
        (  'action'=>'GetToken',
            'cust_code'=>$uid,
        );
        $curl=new Curl();
        $result= $curl->get($http,$data);
        list($TOKEN_ID_STR,$TOKEN_STR)=explode(',',trim($result));
        $token_id=str_replace("TOKEN_ID:",'',$TOKEN_ID_STR);
        $token=str_replace("TOKEN:",'',$TOKEN_STR);
        $data=array(
            'action'=>'QueryAccount',
            'cust_code'=>$uid,
            'token_id'=>$token_id,
            'sign'=>md5($token.$pwd)
        );
        $result= $curl->get($http,$data);
        return $result;
    }


}