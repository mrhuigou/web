<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2014/12/8
 * Time: 15:10
 */
namespace fx\widgets;
use api\models\V1\AdvertiseDetail;
use yii\bootstrap\Widget;

class IndexHot extends Widget{
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		$data = [];
		$advertise = new AdvertiseDetail();
		$focus_position = ['H5-1LTS-DES1'];
		$data['ad_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
//		$focus_position = ['H5-1LTS-DES2'];
//		$data['ad_2'] = [];
		$focus_position = ['H5-1LTS-DES3'];
		$data['ad_3'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-1LTS-DES4'];
		$data['ad_4'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        $focus_position = ['H5-1LTS-DES5'];//替换DES2
        $data['ad_5'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


		return $this->render('index-hot',$data);
	}
}
