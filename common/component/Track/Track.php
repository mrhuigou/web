<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/20
 * Time: 17:17
 */
namespace common\component\Track;
use api\models\V1\CustomerBehavior;
use Yii;
/*
 * @type view,click,collect,uncollect,search_click,comment,share,buy *
 * */
class Track {
	public static function add($item_id,$type){
		if(!Yii::$app->user->isGuest){
			$model=new CustomerBehavior();
			$model->customer_id=Yii::$app->user->getId();
			$model->item_id=$item_id;
			$model->type=$type;
			$model->datetime=time();
			$model->save();
		}
	}
}