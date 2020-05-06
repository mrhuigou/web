<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/27
 * Time: 9:41
 */
namespace fx\widgets\Cart;
use api\models\V1\PromotionDetail;
use yii\bootstrap\Widget;

class Relation extends Widget {
	public $model;

	public function init()
	{
		parent::init();
	}

	public function run()
	{
		return $this->render('relation');
	}
}