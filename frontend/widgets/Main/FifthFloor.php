<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;

class FifthFloor extends \yii\bootstrap\Widget {
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$txt_right_top = ['N2-GHXH-CLASS'];
		$img_left_top1 = ['N2-GHXH-AD'];
		$txt_left_bottom1 = ['N2-GHXH-LINK'];
		$img_middle_left_topn = ['N2-GHXH-SLIDE'];
		$img_rigth_top6 = ['N2-GHXH-FOCUS'];
		$pack_bottomn = ['N2-GHXH-SHOW'];
		$brand_bottomn = ['N2-GHXH-BRAND'];
		$img_promotion_1 = ['N2-GHXH-PROMOTION'];
		$advertise = new AdvertiseDetail();
		$data['txt_right_top_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_right_top);
		$data['img_promotion_1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_promotion_1);
		$data['img_left_top1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_left_top1);
		$data['img_middle_left_topn_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_topn);
		$data['txt_left_bottom1_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_left_bottom1);
		$data['img_rigth_top6_data'] = $advertise->getAdvertiserDetailByPositionCode($img_rigth_top6);
		$data['pack_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($pack_bottomn);
		$data['brand_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($brand_bottomn);
		return $this->render("fifthfloor", ['data' => $data]);
	}
}