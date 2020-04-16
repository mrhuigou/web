<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/28
 * Time: 10:46
 */
namespace backend\controllers;
use api\models\V1\AffiliateTransactionDraw;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\Order;
use common\component\Notice\WxNotice;
use common\component\Payment\WxPay\MchPay;
use yii\base\ErrorException;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\Controller;
class AffiliateApplyDrawController extends Controller{
	public function actionIndex(){
		$model=AffiliateTransactionDraw::find()->orderBy("id desc");

		$dataProvider = new ActiveDataProvider([
			'query' => $model,
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	public function actionView($id){

		$model=AffiliateTransactionDraw::findOne($id);

        $customer_commission_flow = AffiliateTransactionFlow::find()->where(['affiliate_id' => $model->affiliate_id,'status'=>1])->andWhere(['>','amount',0]);
        //收益
        $data['cc_history_total'] = $customer_commission_flow->sum('amount');
        $data['cc_month_total'] = $customer_commission_flow->andWhere(['=','MONTH(FROM_UNIXTIME(create_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(create_at))', date('Y')])->sum('amount');
        $data['cc_week_total'] = $customer_commission_flow->andWhere(['=', 'WEEK(FROM_UNIXTIME(create_at))', date('W')])->andWhere(['=','MONTH(FROM_UNIXTIME(create_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(create_at))', date('Y')])->sum('amount');
        $data['cc_today_total'] = $customer_commission_flow->andWhere(['>=', 'FROM_UNIXTIME(create_at)', date('Y-m-d')])->sum('amount');

        //粉丝
//        $customer_follower = CustomerFollower::find()->where(['customer_id' => $model->customer_id]);
//        $data['cf_history_total'] = $customer_follower->count('follower_id');
//        $data['cf_month_total'] = $customer_follower->andWhere(['=','MONTH(FROM_UNIXTIME(creat_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(creat_at))', date('Y')])->count('follower_id');
//        $data['cf_week_total'] = $customer_follower->andWhere(['=', 'WEEK(FROM_UNIXTIME(creat_at))', date('W')])->andWhere(['=','MONTH(FROM_UNIXTIME(creat_at))', date('m')])->andWhere(['=','year(FROM_UNIXTIME(creat_at))', date('Y')])->count('follower_id');
//        $data['cf_today_total'] = $customer_follower->andWhere(['>=', 'FROM_UNIXTIME(creat_at)', date('Y-m-d')])->count('follower_id');
//        $followers_datas = CustomerFollower::find()->where(['customer_id'=>$model->customer_id])->orderby('id desc')->all();
//        $followers_dataProvider = new ActiveDataProvider([
//            'query' => CustomerFollower::find()->where(['customer_id'=>$model->customer_id])->orderby('id desc'),
//            'pagination' => [
//                'pagesize' => '10',
//            ]
//        ]);
//订单
//        $orders = Order::find()->where(['affiliate_id' => $model->affiliate_id, 'order_type_code' => ['Affiliate','normal']])->andWhere(['>','total',0])->andWhere(['not in','order_status_id',[6,7]])->orderby('order_id desc')->all();
        $orders = Order::find()->where(['affiliate_id' => $model->affiliate_id, 'order_type_code' => ['Affiliate']])->andWhere(['>','total',0])->andWhere(['not in','order_status_id',[6,7]])->orderby('order_id desc')->all();
//        $order_dataProvider = new ActiveDataProvider([
//            'query' => $orders->orderby('order_id desc'),
//            'pagination' => [
//                'pagesize' => '10',
//            ]
//        ]);

        return $this->render('view', [
			'model' => $model,
            'data'=>$data,
//            'followers_datas'=>$followers_datas,
            'orders'=>$orders
		]);
	}
	public function actionDraw(){
		try{
			if(!$model=AffiliateTransactionDraw::findOne(['id'=>\Yii::$app->request->post('id'),'status'=>0])){
				throw new ErrorException('订单已经提过现了！');
			}
			$mch_pay=new MchPay();
			$mch_pay->setParameter('partner_trade_no',$model->code);
			$mch_pay->setParameter('openid',$model->open_id);
			$mch_pay->setParameter('check_name','NO_CHECK');
			$mch_pay->setParameter('amount',$model->amount*100);
			$mch_pay->setParameter('desc','收益提现');
			if($result=$mch_pay->getResult()){
				if(($result['return_code']=='SUCCESS') && ($result['result_code']=='SUCCESS')){
					$model->trade_no=$result['payment_no'];
					$model->remark=Json::encode($result);
					$model->status=1;
					$model->update_at=time();
					$model->save();
					if($flow=AffiliateTransactionFlow::findOne(['type'=>'draw','type_id'=>$model->id])){
						$flow->status=1;
						$flow->update_at=time();
						$flow->save();
					}
					$data = ['status' => 1, 'message' => '对账成功'];
					$notice=new WxNotice();
					$notice->translation($model->open_id,'https://m.mrhuigou.com/user-share/commission',[
						'title'=>'亲，你好，您有一笔'.$model->amount.'元提现金额已转入到你的微信钱包',
						'date'=>date('Y-m-d H:i:s',time()),
						'type'=>'收益提现',
						'amount'=>$model->amount.'元',
					]);
				}else{
					throw new ErrorException($result['err_code_des']."[".$result['err_code']."]");
				}
			}else{
				throw new ErrorException('网络繁忙，稍后重试！');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function sendMsg(){

	}
}