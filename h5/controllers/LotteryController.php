<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/22
 * Time: 15:26
 */
namespace h5\controllers;
use api\models\V1\Lottery;
use api\models\V1\LotteryPrize;
use api\models\V1\LotteryResult;
use common\component\image\Image;
use yii\base\ErrorException;

class LotteryController extends \yii\web\Controller {
	public function actionIndex($id=2)
	{  $this->layout="main_other";
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => '/lottery/index']);
		}
		$model=LotteryResult::find()->where(['lottery_id'=>$id,'customer_id'=>\Yii::$app->user->getId()]);
		$last_id=$model->max('id')?$model->max('id'):0;
		$history=$model->all();
		return $this->render('index',['id'=>$id,'history'=>$history,'last_id'=>$last_id]);
	}
	public function actionApply(){
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('您还没有登录');
			}
			if(!$lottery_id=\Yii::$app->request->post('id')){
				throw new ErrorException('参数错误');
			}
			if(!$lottery=Lottery::findOne($lottery_id)){
				throw new ErrorException('活动不存在');
			}
			if(strtotime($lottery->start_time)>time()){
				throw new ErrorException('抽奖活动暂未开始，稍后在试！');
			}
			if(strtotime($lottery->end_time)<time()){
				throw new ErrorException('抽奖活动已经结束！');
			}
			if($lottery_count=LotteryResult::find()->where(['lottery_id'=>$lottery_id,'customer_id'=>\Yii::$app->user->getId()])->count()){
				throw new ErrorException('您已经抽过了，每个用户只能抽一次！');
			}
			if(!$prize_box=LotteryPrize::find()->where(['lottery_id'=>$lottery_id])->all()){
				throw new ErrorException('当前活动没有设置奖品');
			}
			shuffle($prize_box); //打乱数组顺序
			$arr = [];
			foreach ($prize_box as $key => $val) {
				$qty=max(0,($val->quantity - count($val->result)));
				if($qty){
					$arr[$val->id] =$val->quantity;
				}
			}
			if(!$arr){
				throw new ErrorException('奖品已经被抽光了，下次在来吧！');
			}
			$rid = $this->get_rand($arr); //根据概率获取奖项id
			if($result=LotteryPrize::findOne($rid)){
				$model=new LotteryResult();
				$model->lottery_id=$lottery_id;
				$model->customer_id=\Yii::$app->user->getId();
				$model->lottery_prize_id=$rid;
				$model->creat_at=time();
				$model->save();
				$data = ['status' => 1, 'angle' => $result->angle,'message'=>'恭喜您获得'.$result->title];
			}else{
				throw new ErrorException('网络超时,请重试！');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionResult(){
		$last_id=\Yii::$app->request->post('last_id');
		$lottery_id=\Yii::$app->request->post('lottery_id');
		$data=[];
		$model=LotteryResult::find()->where(['>','id',$last_id?$last_id:0])->andWhere(['lottery_id'=>$lottery_id,'customer_id'=>\Yii::$app->user->getId()])->limit(10)->all();
		if($model){
			foreach ($model as $value){
				$data[]=[
					'id'=>$value->id,
					'nickname'=>$value->customer->nickname,
					'photo'=>Image::resize($value->customer->photo,100,100),
					'datetime'=>date('m/d H:i:s',$value->creat_at),
					'des'=>$value->prize->title,
				];
			}
		}
		\Yii::$app->getResponse()->format = "json";
		return ['data' => $data];
	}


	protected function get_rand($proArr)
	{
		$result = '';
		//概率数组的总概率精度
		$proSum = array_sum($proArr);
		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);
		return $result;
	}
}