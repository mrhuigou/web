<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;

class FourthFloor extends \yii\bootstrap\Widget {
	public function init()
	{
		parent::init();
	}

	public function run()
	{
		$txt_right_top = ['N2-SPTW-CLASS'];
		$img_left_top1 = ['N2-SPTW-AD'];
		$txt_left_bottom1 = ['N2-SPTW-LINK'];
		$img_middle_left_topn = ['N2-SPTW-SLIDE'];
		$img_rigth_top2 = ['N2-SPTW-FOCUS'];
		$pack_bottom3 = ['N2-SPTW-SHOW'];
		$brand_bottom3 = ['N2-SPTW-BRAND'];
		$img_promotion_1 = ['N2-SPTW-PROMOTION'];
		$advertise = new AdvertiseDetail();
		$data['txt_right_top_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_right_top);
		$data['img_promotion_1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_promotion_1);
		$data['img_left_top1_data'] = $advertise->getAdvertiserDetailByPositionCode($img_left_top1);
		$data['img_middle_left_topn_data'] = $advertise->getAdvertiserDetailByPositionCode($img_middle_left_topn);
		$data['txt_left_bottom1_data'] = $advertise->getAdvertiserDetailByPositionCode($txt_left_bottom1);
		$data['img_rigth_top2_data'] = $advertise->getAdvertiserDetailByPositionCode($img_rigth_top2);
		$data['pack_bottom3_data'] = $advertise->getAdvertiserDetailByPositionCode($pack_bottom3);
		$data['brand_bottomn_data'] = $advertise->getAdvertiserDetailByPositionCode($brand_bottom3);
		return $this->render("fourthfloor", ['data' => $data]);
	}
}