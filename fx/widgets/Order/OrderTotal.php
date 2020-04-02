<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */
namespace h5\widgets\Order;
use yii\bootstrap\Widget;

class OrderTotal extends Widget {
	public $order_total;

	public function init()
	{
		parent::init();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ($this->order_total) {
			return $this->render('order-total', ['model' => $this->order_total]);
		}
	}
} 