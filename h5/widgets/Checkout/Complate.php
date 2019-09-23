<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\Checkout;
use api\models\V1\CustomerHongbao;
use yii\bootstrap\Widget;

class Complate extends Widget
{
	public $order_id=0;
	public $model;
	public function init()
	{
		if($this->order_id){
			$order_id=explode(",",$this->order_id);
			$this->model=CustomerHongbao::findOne(['order_id'=>$order_id,'customer_id'=>\Yii::$app->user->getId()]);
		}
		parent::init();
	}



	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if($this->model){
			return $this->render('complate', ['model'=>$this->model]);
		}
	}
} 