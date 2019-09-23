<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;
use yii\bootstrap\Widget;

class Address extends Widget {
	public $address_id;

	public function init()
	{
		if (!$this->address_id = \Yii::$app->session->get('checkout_express_address_id')) {
			$this->address_id=0;
		}
		parent::init();
	}

	public function run()
	{
		$model = \api\models\V1\Address::findOne(['address_id' => $this->address_id, 'customer_id' => \Yii::$app->user->getId()]);
		$list_model = \api\models\V1\Address::find()->where(['customer_id' => \Yii::$app->user->getId()])->orderBy('address_id desc')->all();
		return $this->render('address', ['model' => $model, 'list_model' => $list_model]);
	}
}