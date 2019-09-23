<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/11/21
 * Time: 14:29
 */
namespace h5\controllers;
use api\models\V1\Order;
use Push\Request\V20160801\PushRequest;
use Yii;

class TestController extends \yii\web\Controller {
    public $number = 0;
    public function actionInsertionSort(){
        $array = [3,1,4,5,11,31,22,42,455,112,789,21,90];
        $n = count($array);
        for($i=1; $i<$n;$i++){
            $tmp = $array[$i];
            for ($j = $i-1;$j>= 0; $j--){
                if($tmp< $array[$j]){
                    $array[$j+1] = $array[$j];
                    $array[$j] = $tmp;
                }else{
                    break;
                }
            }
        }
        print_r($array);exit;

    }
    public function actionRunMerageSort(){
        $a = [26, 5, 98, 108, 28, 99, 100, 56, 34, 1 ,90,29];
       // $a = [26, 5,98 ];
        $this->printArray("排序前：",$a);
        $this->MergeSort($a);
        $this->printArray("排序后：",$a);
    }
private function printArray($pre,$a) {
    print_r($pre."<br>");
    for($i=0;$i<count($a);$i++)
    print_r($a[$i]."\t");

}

private function MergeSort(&$a) {
    // TODO Auto-generated method stub
    print_r("开始排序".'<br>');
    $this->Sort($a, 0, count($a) - 1);
}

    private function Sort(&$a, $left, $right) {

        if($right > $left){
            $mid = intval(($left + $right)/2);
            //二路归并排序里面有两个Sort，多路归并排序里面写多个Sort就可以了
            $this->Sort($a, $left, $mid);
            $this->Sort($a, $mid + 1, $right);
            $this->merge($a, $left, $mid, $right);
        }
    }


    private function merge(&$a, $left, $mid, $right) {
echo '======>$left:'.$left;
echo "<br>";
        echo '======>$mid:'.$mid;
        echo "<br>";
        echo '======>$right:'.$right;
        echo "<br>";
        $tmp = [];
        $r1 = $mid + 1;
        $tIndex = $left;
        $cIndex = $left;
        // 逐个归并
        while($left <=$mid && $r1 <= $right) {
            if ($a[$left] <= $a[$r1])
                $tmp[$tIndex++] = $a[$left++]; //left增加
            else
                $tmp[$tIndex++] = $a[$r1++]; //r1 增加
        }
            // 将左边剩余的归并
            while ($left <=$mid) {
                $tmp[$tIndex++] = $a[$left++];
            }
            // 将右边剩余的归并
            while ( $r1 <= $right ) {
                $tmp[$tIndex++] = $a[$r1++];
            }




            print_r("第".(++$this->number)."趟排序:<br>");
            // TODO Auto-generated method stub
            //从临时数组拷贝到原数组
             while($cIndex<=$right){
                 $a[$cIndex]=$tmp[$cIndex];
                 //输出中间归并排序结果
                 print_r($a[$cIndex]."\t");
                    $cIndex++;
                }
                echo "<br>";

        }


    public function actionGetMessage()
    {

        $data = [];
        try {
            if ($data = \Yii::$app->request->get('date')) {
                Yii::error("date:".$data);
            } else {
                Yii::error("null:null");
            }
        } catch (\Exception $e) {

            Yii::error("error:".json_encode($e->getMessage()));;
        }

        if (Yii::$app->request->get('callback')) {
            Yii::$app->getResponse()->format = "jsonp";
            return [
                'data' => $data,
                'callback' => \Yii::$app->request->get('callback')
            ];
        } else {
            Yii::$app->getResponse()->format = "json";
            return ['data' => $data];
        }
    }
    public function actionAliYunPush(){

        $accessKeyId = "LTAIBE7y6V4iRrj6"; //阿里云 Access Key
        $accessKeySecret = "JqXIuplK2MyVgFsIUDGyuQ5b0JLsH8"; //阿里云 accessKeySecret
        $appKey = "24678746";//test_android 24678746

        $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", $accessKeyId, $accessKeySecret);
        $client = new \DefaultAcsClient($iClientProfile);
        $request = new PushRequest();
// 推送目标
        $request->setAppKey($appKey);
        $request->setTarget("ACCOUNT"); //推送目标: DEVICE:推送给设备; ACCOUNT:推送给指定帐号,TAG:推送给自定义标签; ALL: 推送给全部
        $request->setTargetValue("17412"); //根据Target来设定，如Target=device, 则对应的值为 设备id1,设备id2. 多个值使用逗号分隔.(帐号与设备有一次最多100个的限制)
        $request->setDeviceType("ALL"); //设备类型 ANDROID iOS ALL.
        $request->setPushType("NOTICE"); //消息类型 MESSAGE NOTICE
        $request->setTitle("双11促销盛大来袭"); // 消息的标题
        $request->setBody("php body"); // 消息的内容
// 推送配置: iOS
        $request->setiOSBadge(5); // iOS应用图标右上角角标
        $request->setiOSSilentNotification("false");//是否开启静默通知
        $request->setiOSMusic("default"); // iOS通知声音
        $request->setiOSApnsEnv("DEV");//iOS的通知是通过APNs中心来发送的，需要填写对应的环境信息。"DEV" : 表示开发环境 "PRODUCT" : 表示生产环境
        $request->setiOSRemind("false"); // 推送时设备不在线（既与移动推送的服务端的长连接通道不通），则这条推送会做为通知，通过苹果的APNs通道送达一次(发送通知时,Summary为通知的内容,Message不起作用)。注意：离线消息转通知仅适用于生产环境
        $request->setiOSRemindBody("iOSRemindBody");//iOS消息转通知时使用的iOS通知内容，仅当iOSApnsEnv=PRODUCT && iOSRemind为true时有效
        $request->setiOSExtParameters("{'url':'https://m.365jiarun.com/user/index','k':'ios'}"); //自定义的kv结构,开发者扩展用 针对iOS设备
// 推送配置: Android
        $request->setAndroidNotifyType("NONE");//通知的提醒方式 "VIBRATE" : 震动 "SOUND" : 声音 "BOTH" : 声音和震动 NONE : 静音
        $request->setAndroidNotificationBarType(1);//通知栏自定义样式0-100
        $request->setAndroidOpenType("URL");//点击通知后动作 "APPLICATION" : 打开应用 "ACTIVITY" : 打开AndroidActivity "URL" : 打开URL "NONE" : 无跳转
        $request->setAndroidOpenUrl("https://www.365jiarun.com");//Android收到推送后打开对应的url,仅当AndroidOpenType="URL"有效
        $request->setAndroidActivity("com.alibaba.push2.demo.XiaoMiPushActivity");//设定通知打开的activity，仅当AndroidOpenType="Activity"有效
        $request->setAndroidMusic("default");//Android通知音乐
        $request->setAndroidXiaoMiActivity("com.ali.demo.MiActivity");//设置该参数后启动小米托管弹窗功能, 此处指定通知点击后跳转的Activity（托管弹窗的前提条件：1. 集成小米辅助通道；2. StoreOffline参数设为true
        $request->setAndroidXiaoMiNotifyTitle("Mi Title");
        $request->setAndroidXiaoMiNotifyBody("Mi Body");
        $request->setAndroidExtParameters("{'url':'https://m.365jiarun.com/user/index','k':'android'}"); // 设定android类型设备通知的扩展属性
// 推送控制
        $pushTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+3 second'));//延迟3秒发送
        $request->setPushTime($pushTime);
        $expireTime = gmdate('Y-m-d\TH:i:s\Z', strtotime('+1 day'));//设置失效时间为1天
        $request->setExpireTime($expireTime);
        $request->setStoreOffline("false"); // 离线消息是否保存,若保存, 在推送时候，用户即使不在线，下一次上线则会收到
        $response = $client->getAcsResponse($request);
        print_r("\r\n");
        print_r($response);
    }

//    public function actionRunMerageSort(){
//        $arr = array(9,1,5,8,3,7,4,6,2);
//        $this->MergeSort($arr);
//        print_r($arr);
//    }
//    //交换函数
//    function swap(array &$arr,$a,$b){
//        $temp = $arr[$a];
//        $arr[$a] = $arr[$b];
//        $arr[$b] = $temp;
//    }
//
////归并算法总函数
//    function MergeSort(array &$arr){
//        $start = 0;
//        $end = count($arr) - 1;
//        $this->MSort($arr,$start,$end);
//    }
//    function MSort(array &$arr,$start,$end){
//        //当子序列长度为1时，$start == $end，不用再分组
//        if($start < $end){
//            $mid = floor(($start + $end) / 2);	//将 $arr 平分为 $arr[$start - $mid] 和 $arr[$mid+1 - $end]
//            $this->MSort($arr,$start,$mid);			//将 $arr[$start - $mid] 归并为有序的$arr[$start - $mid]
//            $this->MSort($arr,$mid + 1,$end);			//将 $arr[$mid+1 - $end] 归并为有序的 $arr[$mid+1 - $end]
//            $this->Merge($arr,$start,$mid,$end);       //将$arr[$start - $mid]部分和$arr[$mid+1 - $end]部分合并起来成为有序的$arr[$start - $end]
//        }
//    }
//    //归并操作
//    function Merge(array &$arr,$start,$mid,$end){
//        $i = $start;
//        $j=$mid + 1;
//        $k = $start;
//        $temparr = array();
//
//        while($i!=$mid+1 && $j!=$end+1)
//        {
//            if($arr[$i] >= $arr[$j]){
//                $temparr[$k++] = $arr[$j++];
//            }
//            else{
//                $temparr[$k++] = $arr[$i++];
//            }
//        }
//
//        //将第一个子序列的剩余部分添加到已经排好序的 $temparr 数组中
//        while($i != $mid+1){
//            $temparr[$k++] = $arr[$i++];
//        }
//        //将第二个子序列的剩余部分添加到已经排好序的 $temparr 数组中
//        while($j != $end+1){
//            $temparr[$k++] = $arr[$j++];
//        }
//        for($i=$start; $i<=$end; $i++){
//            $arr[$i] = $temparr[$i];
//        }
//    }

}