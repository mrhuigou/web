<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/12/9
 * Time: 16:45
 */
namespace h5\widgets\Ad;
use api\models\V1\AdvertiseDetail;
use yii\bootstrap\Widget;
class Image extends Widget{
	public $code;
	private $details;
	public function init()
	{
		$advertise = new AdvertiseDetail();
		$this->details = $advertise->getAdvertiserDetailByPositionCode($this->code);
		parent::init();
	}
	public function run(){
		return $this->render('image',['model'=>$this->details]);
	}
}