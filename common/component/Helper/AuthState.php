<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/20
 * Time: 9:31
 */
namespace common\component\Helper;
class AuthState{
	public static function create($url){
		$state=md5($url);
		if(!$model=\api\models\V1\AuthState::findOne(['state'=>$state])){
			$model=new \api\models\V1\AuthState();
			$model->state=$state;
			$model->url=$url;
			$model->created_at=time();
			$model->save();
		}
		return $state;
	}
	public static function get($state){
		if($model=\api\models\V1\AuthState::findOne(['state'=>$state])){
			return $model->url;
		}else{
			return '/site/index';
		}
	}
}