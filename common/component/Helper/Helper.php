<?php
/**
 * Created by PhpStorm.
 * User: Arvin
 * Date: 2015/2/4
 * Time: 14:36
 */
namespace common\component\Helper;
use api\models\V1\City;
use api\models\V1\ClubComment;
use api\models\V1\ClubEvents;
use api\models\V1\ClubEventsMember;
use api\models\V1\ClubExperience;
use api\models\V1\ClubGroup;
use api\models\V1\ClubGroupMember;
use api\models\V1\ClubMessage;
use api\models\V1\ClubRelation;
use api\models\V1\Customer;
use api\models\V1\Zone;
use common\component\Curl\Curl;
use common\component\image\Image;
use common\models\District;
use yii\helpers\Json;

class Helper{
    static function  get_device_type(){
         $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
         $type = 'other';
         if(strpos($agent, 'iphone') || strpos($agent, 'ipad')){
             $type = 'ios';
         }
        if(strpos($agent, 'android')){
            $type = 'android';
        }
        return $type;
    }
    static function genTree($items,$id='id',$pid='pid',$son = 'children'){
        $tree = array(); //格式化的树
        $tmpMap = array();  //临时扁平数据

        foreach ($items as $item) {
            $tmpMap[$item[$id]] = $item;
        }

        foreach ($items as $item) {
            if (isset($tmpMap[$item[$pid]])) {
                $tmpMap[$item[$pid]][$son][] = &$tmpMap[$item[$id]];
            } else {
                $tree[] = &$tmpMap[$item[$id]];
            }
        }
        unset($tmpMap);
        return $tree;
    }
    static function Format_date($time){
        $t=time()-$time;
        $f=array(
            '31536000'=>'年',
            '2592000'=>'个月',
            '604800'=>'星期',
            '86400'=>'天',
            '3600'=>'小时',
            '60'=>'分钟',
            '1'=>'秒'
        );
        foreach ($f as $k=>$v)    {
            if (0 !=$c=floor($t/(int)$k)) {
                return $c.$v.'前';
            }
        }
    }
    public static function guid(){
        mt_srand((double)microtime()*10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12);
        return $uuid;
    }

    public static  function auto_charset($fContents, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
            //如果编码相同或者非字符串标量则不转换
            return $fContents;
        }
        if (is_string($fContents)) {
            if (function_exists('mb_convert_encoding')) {
                return mb_convert_encoding($fContents, $to, $from);
            } elseif (function_exists('iconv')) {
                return iconv($from, $to, $fContents);
            } else {
                return $fContents;
            }
        } elseif (is_array($fContents)) {
            foreach ($fContents as $key => $val) {
                $_key = Helper::auto_charset($key, $from, $to);
                $fContents[$_key] = Helper::auto_charset($val, $from, $to);
                if ($key != $_key)
                    unset($fContents[$key]);
            }
            return $fContents;
        }
        else {
            return $fContents;
        }
    }
    public static function arrayToxml($data,$xml=''){
        foreach($data as $key=>$value){
            $xml.="<$key>";
            if(is_array($value)){
                $xml=Helper::arrayToxml($value,$xml);
            }else{
                $xml.=$value;
            }
            $xml.="</$key>";
        }
        return $xml;
    }
    public static function getGps($lats,$lngs, $gps=false, $google=false)//gpg 转百度坐标
    {
        $lat=$lats;
        $lng=$lngs;
        if($gps){
            $c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x=$lng&y=$lat");
        }else if($google){
            $c=file_get_contents("http://api.map.baidu.com/ag/coord/convert?from=2&to=4&x=$lng&y=$lat");
        }else{
            return array($lat,$lng);
        }
        $arr=Json::decode($c);
        if(!($arr['error']))
        {
            $lat=base64_decode($arr['y']);
            $lng=base64_decode($arr['x']);
        }
        return array($lat,$lng);
    }
    public static function getAddressByGps($location){
        $curl=new Curl();
        $result=$curl->get('http://api.map.baidu.com/geocoder/v2/',[
            'ak'=>'qrDz4DGnKDfg0WtdDkOYn0Op',
            'output'=>'json',
            'location'=>$location,
        ]);
        $data=Json::decode($result);
        if($data && $data['status']==0 && isset($data['result']['formatted_address'])){
           return $data['result']['formatted_address'];
        }else{
            return '未知';
        }
    }
    public static function truncate_utf8_string($string, $length, $etc = '...')
    {
        $result = '';
        $string = html_entity_decode(trim(strip_tags($string)), ENT_QUOTES, 'UTF-8');
        $strlen = strlen($string);
        for ($i = 0; (($i < $strlen) && ($length > 0)); $i++)
        {
            if ($number = strpos(str_pad(decbin(ord(substr($string, $i, 1))), 8, '0', STR_PAD_LEFT), '0'))
            {
                if ($length < 1.0)
                {
                    break;
                }
                $result .= substr($string, $i, $number);
                $length -= 1.0;
                $i += $number - 1;
            }
            else
            {
                $result .= substr($string, $i, 1);
                $length -= 0.5;
            }
        }
        $result = htmlspecialchars($result, ENT_QUOTES, 'UTF-8');
        if ($i < $strlen)
        {
            $result .= $etc;
        }
        return $result;
    }
    /*
     * 去掉图片的宽高
     * */
    static function ClearImgWHB($content){
        //去掉图片边宽
        $search = '/(<img.*?)border=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content = preg_replace($search,'$1$3',$content);
        //去掉图片宽度
        $search = '/(<img.*?)width=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content = preg_replace($search,'$1$3',$content);
        //去掉图片高度
        $search = '/(<img.*?)height=(["\'])?.*?(?(2)\2|\s)([^>]+>)/is';
        $content = preg_replace($search,'$1$3',$content);
        return $content;
    }
    static function ClearHtml($content) {
        $content = preg_replace("/<tabel[^>]*>/i", "", $content);
        $content = preg_replace("/<\/tabel>/i", "", $content);
        $content = preg_replace("/<a[^>]*>/i", "", $content);
        $content = preg_replace("/<\/a>/i", "", $content);
        $content = preg_replace("/<p[^>]*>/i", "", $content);
        $content = preg_replace("/<\/p>/i", "", $content);
        $content = preg_replace("/<div[^>]*>/i", "", $content);
        $content = preg_replace("/<\/div>/i", "", $content);
        $content = preg_replace("/style=.+?['|\"]/i",'style="width:100%;"',$content);//去除样式
        $content = preg_replace("/class=.+?['|\"]/i",'',$content);//去除样式
        $content = str_replace("<img", "<img style='width:100%;' class='lazy' ", $content);
        $content = str_replace("src", "data-original", $content);
//    $content = preg_replace("/id=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/lang=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/width=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/height=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/border=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/face=.+?['|\"]/i",'',$content);//去除样式
//    $content = preg_replace("/face=.+?['|\"]/",'',$content);//去除样式 只允许小写 正则匹配没有带 i 参数
        return $content;
    }
    static function Sendums($data){
        $verify_url  = "https://222.173.105.43:10010/riac/com/verify3i";
        $curl=new Curl();
        $curl->setHeader("Content-type","application/x-www-form-urlencoded");
        $result= $curl->post($verify_url,$data);
        return $result;
    }
    static function curl_file_get_contents($durl,$curlPost=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $durl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($ch);//运行curl
        return $data;
    }
    static function Sendsmqxt($telephone, $MessageContent= '') {
        //青岛企信通 短信发送接口测试
        $uid = 'jiarun1'; //用户账号
        $pwd = md5('jiarun1');
        $http = 'http://60.209.7.12:8080/smsServer/submit';             //发送地址
        $mobile = $telephone;
        $data = array
        (
            'CORPID'=>$uid,                         //用户账号
            'CPPW'=>$pwd,                           //密码
            'PHONE'=>$mobile,                               //被叫号码
            'CONTENT'=>$MessageContent."【每日惠购同城】",                             //内容
        );
        $curl=new Curl();
        $curl->setHeader("Content-type","application/x-www-form-urlencoded");
        $result= $curl->post($http,$data);
        if(strtolower(trim($result)) == 'success'){
            return 'success';
        }else{
            return $result;
        }
    }

    static  function str_mid_replace($string) {
        if (! $string || !isset($string[1])) return $string;

        $len = strlen($string);
        $starNum = floor($len / 2);
        $noStarNum = $len - $starNum;
        $leftNum = ceil($noStarNum / 2);
        $rightNum = $noStarNum - $leftNum;

        $result = substr($string, 0, $leftNum);
        $result .= str_repeat('*', $starNum);
        $result .= substr($string, $len-$rightNum);

        return $result;
    }
    static function getPrefix($data){
        if(isset($data['type_name_id']) && $data['type_name_id'] == '1'){
            $prefix = "Exp";
        }elseif(isset($data['type_name_id']) && $data['type_name_id'] == '3'){
            $prefix = "Group";
        }elseif(isset($data['type_name_id']) && $data['type_name_id'] == '4'){
            $prefix = "Events";
        }elseif(isset($data['type_name_id']) && $data['type_name_id'] == '6'){
            $prefix = "Comment";
        }elseif(isset($data['type_name_id']) && $data['type_name_id'] == '17'){
            $prefix = 'Post';
        }else{
            $prefix = '';
        }
        return $prefix;
    }
    //设置redis动态数据
    static function setTrends($customer_id,$data){

        self::setCustomer($customer_id);
        $start_data = "2015-02-07";
        $start_int = strtotime($start_data); //
        $prefix = self::getPrefix($data);

        if(empty($prefix)){
            return ;
        }

        if(isset($data['item_id']) && !empty($data['item_id'])){
            if($prefix == 'Exp'){
                $model = ClubExperience::findOne($data['item_id']);
                self::setExperience($model); //
            }elseif($prefix == 'Group'){
                $model = ClubGroup::findOne($data['item_id']);
                self::setGroup($model); //
            }elseif($prefix == 'Events'){
                $model = ClubEvents::findOne($data['item_id']);
                self::setEvent($model); //
            }
            $thrend_key = $prefix.'_'.$data['item_id'];
        }else{
            return ;
        }




        \Yii::$app->redis->Hset("Trends:".$thrend_key,"who",$data['customer_id']);
        \Yii::$app->redis->Hset("Trends:".$thrend_key,"action",$data['action']);
        \Yii::$app->redis->Hset("Trends:".$thrend_key,"what",$data['item_id']);
        \Yii::$app->redis->Hset("Trends:".$thrend_key,"type_name_id",$data['type_name_id']); //配用字段
        \Yii::$app->redis->Hset("Trends:".$thrend_key,"key",$prefix.':'.$data['item_id']);

        $left_time = time()-$start_int;//用作zadd的排序值
        \Yii::$app->redis->Zadd("TrendsInbox_".$customer_id,$left_time,"Trends:".$thrend_key);

        if(isset($data['events_id']) && !empty($data['event_id'])){
            \Yii::$app->redis->Hset("Trends:".$thrend_key,"events_id",$data['events_id']);

            $eventmember = ClubEventsMember::find()->where(['events_id'=>$data['events_id'],'status'=>1])->asArray()->all();
            if(!empty($eventmember)){
                foreach($eventmember as $et){
                    $left_time = time()-$start_int;//用作zadd的排序值
                    \Yii::$app->redis->Zadd("TrendsInbox_".$et['customer_id'],$left_time,"Trends:".$thrend_key);
                }
            }

        }
        if(isset($data['group_id']) && !empty($data['group_id'])){
            \Yii::$app->redis->Hset("Trends:".$thrend_key,"group_id",$data['group_id']);

            $groupmember = ClubGroupMember::find()->where(['group_id'=>$data['group_id'],'status'=>1])->asArray()->all();
            if(!empty($groupmember)){
                foreach($groupmember as $gt){
                    $left_time = time()-$start_int;//用作zadd的排序值
                    \Yii::$app->redis->Zadd("TrendsInbox_".$gt['customer_id'],$left_time,"Trends:".$thrend_key);
                }
            }
        }

        $clubmember = ClubRelation::find()->where(['customer_id'=>$customer_id ,'status'=>1])->asArray()->all();
        if(!empty($clubmember)){
            foreach($clubmember as $ct){
                $left_time = time()-$start_int;//用作zadd的排序值
                \Yii::$app->redis->Zadd("TrendsInbox_".$ct['friend_customer_id'],$left_time,"Trends:".$thrend_key);
            }
        }


    }
    //设置与我相关
    static  function  setAboutme($customer_id,$data){
        $start_data = "2015-02-07";
        $start_int = strtotime($start_data); //
        $prefix = self::getPrefix($data);
        if(empty($prefix)){
            return ;
        }

        if(isset($data['item_id']) && !empty($data['item_id'])){
            $thrend_key = $prefix.'_'.$data['item_id'];
        }else{
            return ;
        }

        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"type_name_id",$data['type_name_id']);//what
        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"item_id",$data['item_id']); //what_id
        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"customer_id",$data['customer_id']); //A
        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"who",$data['customer_id']); //who = A
        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"action",$data['action']); //do
        \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"key",$prefix.':'.$data['item_id']); //what
        self::setCustomer($data['customer_id']);

        if(isset($data['events_id']) && !empty($data['events_id'])){
            \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"events_id",$data['events_id']);
        }
        if(isset($data['group_id']) && !empty($data['group_id'])){
            \Yii::$app->redis->Hset("Aboutme:".$thrend_key,"group_id",$data['group_id']);
        }
        $left_time = time()-$start_int;
        \Yii::$app->redis->Zadd("AboutmeInbox_".$customer_id,$left_time,"Aboutme:".$thrend_key);
        return true;

    }
    //设置我 个人主页
    static function setMyPage($customer_id,$data){
        $start_data = "2015-02-07";
        $start_int = strtotime($start_data); //
        $prefix = self::getPrefix($data);
        if(empty($prefix)){
            return ;
        }

        if(isset($data['item_id']) && !empty($data['item_id'])){
            $thrend_key = $prefix.'_'.$data['item_id'];
        }else{
            return ;
        }
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"type_name_id",$data['type_name_id']);//what
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"item_id",$data['item_id']); //what_id
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"customer_id",$customer_id); //A
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"who",$data['customer_id']);//who = A
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"action",$data['action']); //do
        \Yii::$app->redis->Hset("MyPage:".$thrend_key,"key",$prefix.':'.$data['item_id']); //what
        $left_time = time()-$start_int;
        \Yii::$app->redis->Zadd("MyPageOutbox_".$customer_id,$left_time,"MyPage:".$thrend_key);

        self::setCustomer($customer_id);
    }
    static function setExperience($data){
        if(isset($data->exp_id)){
            $exp_id = $data->exp_id;
            \Yii::$app->redis->Hset("Exp:".$exp_id,'exp_id',$exp_id);
            \Yii::$app->redis->Hset("Exp:".$exp_id,'item_id',$exp_id);
            \Yii::$app->redis->Hset("Exp:".$exp_id,'type_name_id',1);
        }else{
            return ;
        }
        if(isset($data->title) && !empty($data->title)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'title',$data->title);
        }
        if(isset($data->content) && !empty($data->content)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'content',$data->content);
        }
        if(isset($data->cover_image)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'cover_image',$data->cover_image);
        }
        if(isset($data->image_array)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'image_array',$data->image_array);
        }
        if(isset($data->create_time)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'create_time',$data->create_time);
        }
        if(isset($data->customer_id)){
            \Yii::$app->redis->Hset("Exp:".$exp_id,'customer_id',$data->customer_id);
            self::setCustomer($data->customer_id);
        }

    }

    static function setEvent($data){
        if(isset($data->events_id)){
            $events_id = $data->events_id;
            \Yii::$app->redis->Hset("Events:".$events_id,'events_id',$events_id);
            \Yii::$app->redis->Hset("Events:".$events_id,'item_id',$events_id);
            \Yii::$app->redis->Hset("Events:".$events_id,'type_name_id',4);
        }else{
            return ;
        }
        if(isset($data->title) && !empty($data->title)){
            \Yii::$app->redis->Hset("Events:".$events_id,'title',$data->title);
        }
        if(isset($data->status) && !empty($data->status)){
            \Yii::$app->redis->Hset("Events:".$events_id,'status',$data->status);
        }
        if(isset($data->start_time) && !empty($data->start_time)){
            \Yii::$app->redis->Hset("Events:".$events_id,'start_time',$data->start_time);
        }
        if(isset($data->address) && !empty($data->address)){
            \Yii::$app->redis->Hset("Events:".$events_id,'address',$data->address);
        }
        if(isset($data->member_gender) && !empty($data->member_gender)){
            \Yii::$app->redis->Hset("Events:".$events_id,'member_gender',$data->member_gender);
        }
        if(isset($data->cover_image) && !empty($data->cover_image)){
            \Yii::$app->redis->Hset("Events:".$events_id,'cover_image',$data->cover_image);
        }
        if(isset($data->description) && !empty($data->description)){
            \Yii::$app->redis->Hset("Events:".$events_id,'description',$data->description);
        }
        if(isset($data->by_customer_id) && !empty($data->by_customer_id)){
            \Yii::$app->redis->Hset("Events:".$events_id,'by_customer_id',$data->by_customer_id);
            self::setCustomer($data->by_customer_id);
        }
        if(isset($data->created_at) && !empty($data->created_at)){
            \Yii::$app->redis->Hset("Events:".$events_id,'create_time',$data->created_at);
        }

    }
    static function setGroup($data){
        if(isset($data->group_id)){
            $group_id = $data->group_id;
            \Yii::$app->redis->Hset("Group:".$group_id,'group_id',$group_id);
            \Yii::$app->redis->Hset("Group:".$group_id,'item_id',$group_id);
            \Yii::$app->redis->Hset("Group:".$group_id,'type_name_id',3);
        }else{
            return ;
        }
        if(isset($data->title) && !empty($data->title)){
            \Yii::$app->redis->Hset("Group:".$group_id,'title',$data->title);
        }
        if(isset($data->description) && !empty($data->description)){
            \Yii::$app->redis->Hset("Group:".$group_id,'description',$data->description);
        }
        if(isset($data->logo)){
            \Yii::$app->redis->Hset("Group:".$group_id,'logo',$data->logo);
        }
        if(isset($data->create_time)){
            \Yii::$app->redis->Hset("Group:".$group_id,'create_time',$data->create_at);
        }
        if(isset($data->customer_id)){
            \Yii::$app->redis->Hset("Group:".$group_id,'customer_id',$data->customer_id);
            self::setCustomer($data->customer_id);
        }
    }
    static function setComment($data){
        if(isset($data->comment_id) && !empty($data->comment_id)){
            $comment_id = $data->comment_id;
            \Yii::$app->redis->Hset("Comment:".$comment_id,'comment_id',$comment_id);
        }else{
            return ;
        }
        if(isset($data->customer_id) && !empty($data->customer_id)){
            self::setCustomer($data->customer_id);
            \Yii::$app->redis->Hset("Comment:".$comment_id,'customer_id',$data->customer_id);
        }
        if(isset($data->type_name_id) && !empty($data->type_name_id)){
            \Yii::$app->redis->Hset("Comment:".$comment_id,'type_name_id',$data->type_name_id);
        }
        if(isset($data->content_id) && !empty($data->content_id)){
            \Yii::$app->redis->Hset("Comment:".$comment_id,'content_id',$data->content_id);
        }
        if(isset($data->content) && !empty($data->content)){
            \Yii::$app->redis->Hset("Comment:".$comment_id,'content',$data->content);
        }
        if(isset($data->create_time) && !empty($data->create_time)){
            \Yii::$app->redis->Hset("Comment:".$comment_id,'create_time',$data->create_time);
        }


    }

    //设置用户redis数据
    static function setCustomer($customer_id){
        $customer = Customer::findOne($customer_id);
        $age = date("Y") - date("Y",strtotime($customer->birthday));
        \Yii::$app->redis->Hset("Customer:".$customer_id,'nickname',$customer->nickname);
        $photo = Image::resize($customer->photo,100,100);
        \Yii::$app->redis->Hset("Customer:".$customer_id,'photo',$photo);
        \Yii::$app->redis->Hset("Customer:".$customer_id,'gender',$customer->gender);
        \Yii::$app->redis->Hset("Customer:".$customer_id,'birthday',$customer->birthday);
        \Yii::$app->redis->Hset("Customer:".$customer_id,'age',strval($age));

    }
    static function setMessage($from_customer_id,$to_customer_id,$data){
        $message = new ClubMessage();
        $message->from_customer_id = $from_customer_id;
        $message->to_customer_id = $to_customer_id;
        $message->is_read = 0;
        $message->is_agree = 0;
        $message->is_del = 0;
        if(isset($data['from_group_id'])){
            $message->from_group_id = $data['from_group_id'];
        }
        if(isset($data['from_events_id'])){
            $message->from_events_id = $data['from_events_id'];
        }
        $message->content =serialize($data['content']) ;
        $message->post_time = date("Y-m-d H:i:s");
        $message->is_system = $data['is_system'];
        $message->need_verify = $data['need_verify'];
        $message->save();

    }
    /*
     * 获取 hash 的键值对
     * param hash like Customer:314  Exp:41 Group:1 Events:1
     * */

    static function getKeyValue($hash){
        $result = [];
        $keys_array =  \Yii::$app->redis->hkeys($hash);
        //print_r($keys_array);exit;
        if(!empty($keys_array)){
            foreach($keys_array as $k => $v){
                $value = \Yii::$app->redis->hmget($hash,$v);
                $result[$v] = $value[0];//iconv("gbk", "UTF-8", $value[0]) ;

            }
        }
        if(!empty($result)){
            return $result;
        }else{
            $item = explode(":",$hash);
            if(count($item) == 2){
                $type = $item[0];
                $id = $item[1];
                if(strtolower($type) == "customer"){
                    $result = Helper::getCustomerformat($id);
                }else if(strtolower($type) == "exp"){
                    $datas = ClubExperience::find()->where(["exp_id"=> $id,"is_del"=>0])->one();
                    if(isset($datas) && !empty($datas)) {
                        self::setExperience($datas);
                        $result['exp_id'] = $datas->exp_id;
                        $result['title'] = $datas->title;
                        $result['content'] = $datas->content;
                        $result['customer'] = Helper::getCustomerformat($datas->customer_id);
                        $result['cover_image'] = Image::resize($datas->cover_image, 0, 0);
                        $result['create_time'] = $datas->create_time;
                        $result['last_update_time'] = $datas->last_update_time;
                    }
                } else if(strtolower($type) == "group"){
                    $datas = ClubGroup::find()->where(["group_id"=> $id])->one();
                    if(isset($datas) && !empty($datas)){
                        self::setGroup($datas);
                        $result['group_id'] = $datas->group_id;
                        $result['title'] = $datas->title;
                        $result['description'] = $datas->description;
                        $result['customer'] =  Helper::getCustomerformat($datas->customer_id);
                        // $result['cover_image'] =  Image::resize($datas->cover_image,0,0);
                        $result['created_time'] = $datas->created_at;
                        $result['last_update_time'] = $datas->updated_at;
                        $result['logo'] = Image::resize($datas->logo,0,0);
                    }

                }else if(strtolower($type) == "events"){

                    $datas = ClubEvents::find()->where(["events_id"=> $id])->one();
                    if(isset($datas) && !empty($datas)) {
                        self::setEvent($datas);
                        $result['events_id'] = $datas->events_id;
                        $result['title'] = $datas->title;
                        $result['description'] = $datas->description;
                        $result['customer'] = Helper::getCustomerformat($datas->by_customer_id);
                        $result['cover_image'] = Image::resize($datas->cover_image, 0, 0);
                        $result['created_time'] = $datas->created_at;
                        $result['last_update_time'] = $datas->updated_at;
                        $result['start_time'] = $datas->start_time;
                        $result['end_time'] = $datas->end_time;
                        $result['member_gender'] = $datas->member_gender;
                    }
                }else if(strtolower($type) == "comment"){
                    $datas = ClubComment::find()->where(["comment_id"=> $id])->one();
                    if(isset($datas) && !empty($datas)) {
                        self::setComment($datas);
                        $result['comment_id'] = $datas->comment_id;
                        $result['content'] = $datas->content;
                        $result['type_name_id'] = $datas->type_name_id;
                        $result['content_id'] = $datas->content_id;
                        $result['customer'] = Helper::getCustomerformat($datas->customer_id);

                        $result['created_time'] = $datas->create_time;
                    }

                }

            }


        }
       return $result;
    }

    /*
     * 格式化用户数据
     * */
    static function getCustomerformat($customer_id){
        $customer = Customer::findOne($customer_id);
        $result = array();
        if(!empty($customer)){
            if(!empty($customer->city_id)){
                $city_info = City::findOne($customer->city_id);
                if(!empty($city_info)){
                    $city_name = $city_info->name;
                }else{
                    $city_name = "保密";
                }
            }else{
                $city_name = "保密";
            }
            if(!empty($customer->zone_id)){
                $zone_info = Zone::findOne($customer->zone_id);
                if(!empty($zone_info)){
                    $zone_name = $zone_info->name;
                }else{
                    $zone_name = "保密";
                }

            }else{
                $zone_name = "保密";
            }
            if(!empty($customer->district_id)){
                $district_info = District::findOne($customer->district_id);

                if(!empty($district_info)){
                    $district_name = $district_info->name;
                }else{
                    $district_name = "保密";
                }
            }else{
                $district_name = "保密";
            }
            if(!isset($customer->nickname) || empty($customer->nickname )){
                $nickname = "匿名";
            }else{
                $nickname = $customer->nickname;
            }
            if(!isset($customer->gender) || empty($customer->gender )){
                $gender = "保密";
            }else{
                $gender = $customer->gender;
            }
            if(!isset($customer->firstname) || empty($customer->firstname )){
                $firstname = "匿名";
            }else{
                $firstname = $customer->firstname;
            }
            $age = date("Y") - date("Y",strtotime($customer->birthday));
            $idcard_validate = isset($customer->idcard_validate) ? $customer->idcard_validate : 0;
            $business_validate = isset($customer->authen_business) ? $customer->authen_business : 0;
            $result = array(
                'customer_id' => $customer_id,
                "nickname"  => $nickname,
                "firstname" => $firstname ,
                "photo"     => Image::resize($customer->photo,100,100),
                "gender"    =>$gender,
                "age"  => strval($age),
                "customer_id" => strval($customer->customer_id),
                "zone"           => $zone_name,
                "city"          => $city_name,
                "district"      => $district_name,
                'idcard_validate' => strval($idcard_validate),
                'business_validate' => strval($business_validate),
            );
        }else{
            $result = array(
                'customer_id' => $customer_id,
                "nickname"  => "" ,
                "firstname" => "" ,
                "photo"     => Image::resize("noimage.jpg",100,100),
                "gender"    => "",
                "age"  => "",
                "customer_id" => "",
                "zone"           => "保密",
                "city"          =>  "保密",
                "district"      => "保密",
                'idcard_validate' => "0",
                'business_validate' => "0",
            );
        }
        return $result;
    }


    static function getFirstchar($s0){
        $fchar = ord($s0{0});
        if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
        $s1 = iconv("UTF-8","gb2312", $s0);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $s0){$s = $s1;}else{$s = $s0;}
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if($asc >= -20319 and $asc <= -20284) return "A";
        if($asc >= -20283 and $asc <= -19776) return "B";
        if($asc >= -19775 and $asc <= -19219) return "C";
        if($asc >= -19218 and $asc <= -18711) return "D";
        if($asc >= -18710 and $asc <= -18527) return "E";
        if($asc >= -18526 and $asc <= -18240) return "F";
        if($asc >= -18239 and $asc <= -17923) return "G";
        if($asc >= -17922 and $asc <= -17418) return "I";
        if($asc >= -17417 and $asc <= -16475) return "J";
        if($asc >= -16474 and $asc <= -16213) return "K";
        if($asc >= -16212 and $asc <= -15641) return "L";
        if($asc >= -15640 and $asc <= -15166) return "M";
        if($asc >= -15165 and $asc <= -14923) return "N";
        if($asc >= -14922 and $asc <= -14915) return "O";
        if($asc >= -14914 and $asc <= -14631) return "P";
        if($asc >= -14630 and $asc <= -14150) return "Q";
        if($asc >= -14149 and $asc <= -14091) return "R";
        if($asc >= -14090 and $asc <= -13319) return "S";
        if($asc >= -13318 and $asc <= -12839) return "T";
        if($asc >= -12838 and $asc <= -12557) return "W";
        if($asc >= -12556 and $asc <= -11848) return "X";
        if($asc >= -11847 and $asc <= -11056) return "Y";
        if($asc >= -11055 and $asc <= -10247) return "Z";
        return null;
    }
    /***
     * $arr = array(
    ‘d’ => array(‘id’ => 5, ‘name’ => 1, ‘age’ => 7),
    ‘b’ => array(‘id’ => 2,’name’ => 3,’age’ => 4),
    ‘a’ => array(‘id’ => 8,’name’ => 10,’age’ => 5),
    ‘c’ => array(‘id’ => 1,’name’ => 2,’age’ => 2)
    );print_r(multi_array_sort($arr,’age’));
     * @param $multi_array
     * @param $sort_key
     * @param int $sort
     * @return array|bool
     */
   static function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
        if(is_array($multi_array)){
            $key_array = array();
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }

    static function getWeatherByBaiduAPI($location){

        $y_http = 'http://api.map.baidu.com/telematics/v3/weather?ak=9fbbd87c6d43b3742335204bd33da8cc';
        $ak = '';
        $y_data = array
        (
            'location' => $location,
            'output'=> 'json',    //用户密码
        );
        $result = self::curl_file_get_contents($y_http,http_build_query($y_data));
        return $result;
    }
        static  function gotomail($mail){
        $t=explode('@',$mail);
        $t=strtolower($t[1]);
        if($t=='163.com'){
            return 'mail.163.com';
        }else if($t=='vip.163.com'){
            return 'vip.163.com';
        }else if($t=='126.com'){
            return 'mail.126.com';
        }else if($t=='qq.com'||$t=='vip.qq.com'||$t=='foxmail.com'){
            return 'mail.qq.com';
        }else if($t=='gmail.com'){
            return 'mail.google.com';
        }else if($t=='sohu.com'){
            return 'mail.sohu.com';
        }else if($t=='tom.com'){
            return 'mail.tom.com';
        }else if($t=='vip.sina.com'){
            return 'vip.sina.com';
        }else if($t=='sina.com.cn'||$t=='sina.com'){
            return 'mail.sina.com.cn';
        }else if($t=='tom.com'){
            return 'mail.tom.com';
        }else if($t=='yahoo.com.cn'||$t=='yahoo.cn'){
            return 'mail.cn.yahoo.com';
        }else if($t=='tom.com'){
            return 'mail.tom.com';
        }else if($t=='yeah.net'){
            return 'www.yeah.net';
        }else if($t=='21cn.com'){
            return 'mail.21cn.com';
        }else if($t=='hotmail.com'){
            return 'www.hotmail.com';
        }else if($t=='sogou.com'){
            return 'mail.sogou.com';
        }else if($t=='188.com'){
            return 'www.188.com';
        }else if($t=='139.com'){
            return 'mail.10086.cn';
        }else if($t=='189.cn'){
            return 'webmail15.189.cn/webmail';
        }else if($t=='wo.com.cn'){
            return 'mail.wo.com.cn/smsmail';
        }else if($t=='139.com'){
            return 'mail.10086.cn';
        }else {
            return '';
        }
    }
   static function generate_code($length = 6) {
        $min = pow(10 , ($length - 1));
        $max = pow(10, $length) - 1;
        return rand($min, $max);
}
}
