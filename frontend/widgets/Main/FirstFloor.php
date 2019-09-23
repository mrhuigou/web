<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;

class FirstFloor extends \yii\bootstrap\Widget{
	public function init()
	{
		parent::init();
	}

	public function run(){
		$img_left1 = array('N2-TSTC-AD');
		$img_middle_left_top1 = array('N2-TSTC-SLIDE');
		$img_middle_left_bottom1 = array('N2-TSTC-BRAND');
		$img_middle_right_top2 = array('N2-TSTC-FOCUS');
		$img_rigth_top2 = array('N2-TSTC-ACTIVE');
		$pack_right_bottom3 = array('N2-TSTC-SHOW');
		$img_promotion_1 = array('N2-TSTC-PROMOTION');

		$advertise = new AdvertiseDetail();

		$data['img_promotion_1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_promotion_1);


		$data['img_left1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_left1);

		$data['img_middle_left_top1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_top1);


		$data['img_middle_left_bottom1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_bottom1);


		$data['img_middle_right_top2_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_right_top2);

		$data['img_rigth_top2_data'] = $advertise->getAdvertiserDetailByPositionCode($img_rigth_top2);

		$data['pack_right_bottom3_data'] = $advertise->getAdvertiserDetailByPositionCode($pack_right_bottom3);

		return $this->render('firstfloor',['data'=>$data]);
	}
}