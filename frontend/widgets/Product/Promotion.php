<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/6/22
 * Time: 10:11
 */
namespace frontend\widgets\Product;
use yii\bootstrap\Widget;

class Promotion extends Widget{
	public $model;
	public function init()
	{
		parent::init();
	}
	public function run(){
		return $this->render('promotion',['model'=>$this->model->promotion]);
	}
}