<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;
use yii\bootstrap\Widget;

class News extends Widget{
	public $category_id=1;
	private $model;

	public function init()
	{
		$this->model=\api\models\V1\News::find()->where(['news_category_id'=>$this->category_id,'channel'=>1])->orderBy('sort_order asc')->limit(10)->all();
		parent::init();
	}
	public function run()
	{
		return $this->render('news',['model'=>$this->model,'NewModel'=>$this]);
	}
}