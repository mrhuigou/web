<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/6/27
 * Time: 9:41
 */
namespace h5\widgets\Share;
use api\models\V1\CustomerHongbao;
use yii\bootstrap\Widget;
class Order extends Widget{
	public $model;
	public function init()
	{
		parent::init();
	}
	public function run(){
		if($this->model && $this->model->status){
			if($this->model->total>1){
				$hongbao=CustomerHongbao::find()->where(['customer_id'=>\Yii::$app->user->getId(),'status'=>0])->orderBy('id desc')->one();
				if($hongbao){
					return $this->render('order',['hongbao'=>$hongbao]);
				}
			}
		}

	}
}