<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;

class SecondFloor extends \yii\bootstrap\Widget {
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$txt_right_top = ['N2-SXSC-CLASS'];
		$brand_bottomn = ['N2-SXSC-BRAND'];
		$img_left_top1 = ['N2-SXSC-AD'];
		$txt_left_bottom1 = ['N2-SXSC-LINK'];
		$img_middle_left_topn = ['N2-SXSC-SLIDE'];
		$img_rigth_top2 = ['N2-SXSC-FOCUS'];
		$pack_bottom3 = ['N2-SXSC-SHOW'];
		$img_promotion_1 = ['N2-SXSC-PROMOTION'];
		$advertise = new AdvertiseDetail();
		$data['txt_right_top_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_right_top);
		$data['img_promotion_1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_promotion_1);
		$data['img_left_top1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_left_top1);
		$data['img_middle_left_topn_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_topn);
		$data['txt_left_bottom1_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_left_bottom1);
		$data['img_rigth_top2_data'] = $advertise->getAdvertiserDetailByPositionCode($img_rigth_top2);
		$data['pack_bottom3_data'] = $advertise->getAdvertiserDetailByPositionCode($pack_bottom3);
		$data['brand_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($brand_bottomn);
		return $this->render('secondfloor', ['data' => $data]);
	}
}