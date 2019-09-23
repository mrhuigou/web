<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/10/14
 * Time: 9:07
 */
namespace common\component\Wx;
use api\models\V1\WeixinScans;
use common\component\Curl\Curl;
use Yii;
use yii\helpers\Json;

class WxScans{
	public function creatScan($scene_str){
		return Yii::$app->wechat->createQrCode(['action_name'=>'QR_LIMIT_STR_SCENE','action_info'=>['scene'=>['scene_str'=>$scene_str]]]);
	}
	public function getScan($ticket){
		$model=WeixinScans::findOne(['ticket'=>$ticket]);
		return $model;
	}
	public function creatTmpScan($scene_str_id,$expire_seconds){
		return Yii::$app->wechat->createQrCode(['expire_seconds'=>$expire_seconds,'action_name'=>'QR_SCENE','action_info'=>['scene'=>['scene_id'=>$scene_str_id]]]);
	}

}