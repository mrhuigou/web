<?php
namespace common\component\Wx;
use common\component\Curl\Curl;
use Yii;
class WxSdk{
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret) {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }
    public function getBaseUserInfo($openid){
        $accessToken = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openid&lang=zh_CN";
        $res = json_decode($this->httpGet($url));
        return $res;
    }
    public function getSignPackage($url="") {
        $jsapiTicket = $this->getJsApiTicket();

        // 注意 URL 一定要动态获取，不能 hardcode.
	    if(!$url){
		    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	    }
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId"     => $this->appId,
            "nonceStr"  => $nonceStr,
            "timestamp" => $timestamp,
            "url"       => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
    private function getJsApiTicket() {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例

        if(\Yii::$app->cache->get('Wechat_JsApi_Ticket')){
            $ticket = \Yii::$app->cache->get('Wechat_JsApi_Ticket');
        }else{
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            \Yii::$app->cache->set('Wechat_JsApi_Ticket',$ticket,7000);
        }
        return $ticket;
    }
    public function getAccessToken() {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例

	    return \Yii::$app->wechat->getAccessToken();
    }

    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }

    public function CreatMenu($data){
        $accessToken = $this->getAccessToken();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$accessToken;
        $curl=new Curl();
        $curl->setUserAgent("Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        $result=$curl->post($url,$data);
        return $result;
    }
    public function DelMenu(){
        $access_token=$this->getAccesstoken();
        $curl=new Curl();
        $result=  $curl->get("https://api.weixin.qq.com/cgi-bin/menu/delete",['access_token'=>$access_token]);
        return $result;
    }
    public function UploadFile($filepath,$type){
        $access_token=$this->getAccesstoken();
        $curl=new Curl();
        $filedata = array("file1"  => "@".$filepath);
        $url = "http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token=$access_token&type=$type";
        $result=$curl->post($url,$filedata);
        return $result;
    }
   public function DownloadFile($media_id){
       $access_token=$this->getAccesstoken();
       $curl=new Curl();
       $reslut=$curl->get('https://api.weixin.qq.com/cgi-bin/media/get',['access_token'=>$access_token,'media_id'=>$media_id]);
       $responseHeaders=$curl->response_headers;
       $ext="";
       if (isset($responseHeaders['Content-Type'])) {
           if(preg_match('{image/(\w+)}i', $responseHeaders['Content-Type'], $extmatches)) {
               $ext = $extmatches[1];
           }elseif(preg_match('{audio/(\w+)}i', $responseHeaders['Content-Type'], $extmatches)){
               $ext = $extmatches[1];
           }
       }else{
           $ext="error";
       }
       $filename="/tmp/".uniqid().".$ext";
       $this->SaveFile($filename,$reslut);
       if(file_exists($filename)){
           return $filename;
       }else{
           return false;
       }
    }
    private function SaveFile($filename, $filecontent)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file){
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }
}