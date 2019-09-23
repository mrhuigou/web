<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/27
 * Time: 9:41
 */
namespace h5\widgets\Product;
use yii\bootstrap\Widget;
class Relation extends Widget{
	public $model;
	public function init()
	{
		parent::init();
	}
	public function run(){
		return $this->render('relation',['item_id'=>$this->model->product_base_id]);
	}
}