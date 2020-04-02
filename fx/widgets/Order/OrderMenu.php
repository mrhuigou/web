<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace fx\widgets\Order;
use api\models\V1\Order;
use yii\bootstrap\Widget;

class OrderMenu extends Widget {
	public $order_id;
	public $model;

	public function init()
	{
		if($model=Order::findOne(['order_id'=>$this->order_id])){
			$this->model=$model;
		}
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->model) {
			return $this->render('order-menu', ['model' => $this->model]);
		}
	}
}