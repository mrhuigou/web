<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace fx\widgets;
use api\models\V1\AdvertiseDetail;
use api\models\V1\AffiliatePlan;
use yii\bootstrap\Widget;

class IndexHot extends Widget{
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		$data = [];
		$advertise = new AffiliatePlan();
		$focus_position = ['AF-2F'];
		$data['ad_1'] = $advertise->getAffiliatePlanDetailByPositionCode($focus_position);

		return $this->render('index-hot',$data);
	}
}
