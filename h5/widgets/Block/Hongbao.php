<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;
use yii\bootstrap\Widget;

class Hongbao extends Widget{
	public $code;

	public function init()
	{
		//$this->model=\api\models\V1\News::find()->where(['news_category_id'=>$this->category_id,'channel'=>1])->orderBy('news_id desc')->limit(10)->all();
		parent::init();
	}
	public function run()
	{

		//return $this->render('hongbao',['code'=>$this->code]);
	}
}