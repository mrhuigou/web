<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 9:54
 */
namespace frontend\widgets\Main;
use api\models\V1\AdvertiseDetail;
use api\models\V1\CategoryDisplay;
use api\models\V1\News;

class Home extends \yii\bootstrap\Widget{
	public function init()
	{
		parent::init();
	}

	public function run(){
		$category_arrays = CategoryDisplay::find()->where(['parent_id'=>'501'])->orderBy('sort_order')->all();
		$data = array();
		/*获取滚动banner*/
		$silde_position = array('N2-MAIN-SLIDE');
		$advertise = new AdvertiseDetail();
		$data['silde_position_data'] = $advertise->getAdvertiserDetailByPositionCode($silde_position);
		//新闻
		$data['news'] = News::find()->where(['channel'=>0,'status'=>1])->orderBy('sort_order ASC,date_added DESC')->limit(5)->all();
		$focus_position = array('N2-MAIN-FOCUS');
		$data['focus_position_data'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

		return $this->render("home",['category_arrays'=> $category_arrays,'data'=>$data]);


	}
}