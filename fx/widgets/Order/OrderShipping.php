<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\Order;
use yii\bootstrap\Widget;
class OrderShipping extends Widget
{
	public function init()
	{
		parent::init();
	}
	/**
	 * @inheritdoc
	 */
	public function run()
	{
		return $this->render('order_shipping');
	}
}