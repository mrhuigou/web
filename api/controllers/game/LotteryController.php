<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/18
 * Time: 17:41
 */
namespace api\controllers\game;
use api\models\V1\LotteryCustomer;
use common\component\image\Image;
use \yii\rest\Controller;
class LotteryController extends  Controller{

	public function actionIndex($last_id=0){
		$data=[];
		$model=LotteryCustomer::find()->where(['>','id',$last_id])->all();
		if($model){
			foreach ($model as $value){
				$data[]=[
					'id'=>$value->id,
					'name'=>$value->customer->nickname,
					'photo'=>Image::resize($value->customer->photo,100,100)
				];
			}
		}
		if (\Yii::$app->request->get('callback')) {
			\Yii::$app->getResponse()->format = "jsonp";
			return [
				'data' => $data,
				'callback' => \Yii::$app->request->get('callback')
			];
		} else {
			\Yii::$app->getResponse()->format = "json";
			return ['data' => $data];
		}
	}

}