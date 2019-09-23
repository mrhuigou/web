<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;
use api\models\V1\PrizeBox;
use api\models\V1\PrizeChance;
use yii\bootstrap\Widget;

class Game extends Widget{
	public $customer_id;
	public function init()
	{
		parent::init();
	}
	public function run()
	{
		if ($chance = PrizeChance::find()->where(['and', 'customer_id=' . $this->customer_id, 'status=0', "expiration_time>'" . date('Y-m-d H:i:s', time()) . "'"])->count()) {
			if (($model = PrizeBox::find()->where(['status' => 1, 'type' => 'draw'])->limit(8)->orderBy('id asc')->all()) && count($model) == 8) {
				return $this->render('game',['model'=>$model]);
			}
		}
	}
}