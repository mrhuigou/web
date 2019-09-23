<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets;
use api\models\V1\AdvertiseDetail;

class HeaderTopAd extends \yii\bootstrap\Widget{
	public function init()
	{
		parent::init();
	}

	public function run(){
		$silde_position = array('N2-SHOW-AD');
		$advertise = new AdvertiseDetail();
		if($model=$advertise->getAdvertiserDetailByPositionCode($silde_position)) {
			return $this->render("header-top-ad",['data'=>$model]);
		}

	}
}