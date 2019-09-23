<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;

class SixthFloor extends \yii\bootstrap\Widget {
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$txt_right_top = ['N2-RYBH-CLASS'];
		$img_left_top1 = ['N2-RYBH-AD'];
		$txt_left_bottom1 = ['N2-RYBH-LINK'];
		$img_middle_left_topn = ['N2-RYBH-SLIDE'];
		$img_rigth_top6 = ['N2-RYBH-FOCUS'];
		$pack_bottomn = ['N2-RYBH-SHOW'];
		$brand_bottomn = ['N2-RYBH-BRAND'];
		$img_promotion_1 = ['N2-RYBH-PROMOTION'];
		$advertise = new AdvertiseDetail();
		$data['txt_right_top_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_right_top);
		$data['img_promotion_1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_promotion_1);
		$data['img_left_top1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_left_top1);
		$data['img_middle_left_topn_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_topn);
		$data['txt_left_bottom1_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_left_bottom1);
		$data['img_rigth_top6_data'] = $advertise->getAdvertiserDetailByPositionCode($img_rigth_top6);
		$data['pack_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($pack_bottomn);
		$data['brand_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($brand_bottomn);
		return $this->render("sixthfloor", ['data' => $data]);
	}
}