<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace fx\widgets\Block;
use api\models\V1\Affiliate;
use api\models\V1\AffiliateTransactionFlow;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerCommission;
use api\models\V1\CustomerCommissionFlow;
use api\models\V1\CustomerFollower;
use api\models\V1\Order;
use yii\bootstrap\Widget;

class UserShareBlock extends Widget{

	public function init()
	{
		parent::init();
	}
	public function run()
	{

	    if(\Yii::$app->request->get('sourcefrom') != 'zhqd' && !\Yii::$app->session->get('source_from_agent_wx_xcx')){
            $commission_total=0;
            $follower_total=0;
            $order_total=0;
            if($model=Affiliate::findOne(['customer_id'=>\Yii::$app->user->getId()])) {
                $commission_total = AffiliateTransactionFlow::find()->where(['affiliate_id' => \Yii::$app->user->identity->getAffiliateId(),'status'=>1])->andWhere(['>','amount',0])->sum('amount');
//                $follower_total = CustomerFollower::find()->where(['customer_id' => \Yii::$app->user->getId()])->count('follower_id');
//                $order_total = Order::find()->where(['source_customer_id' => \Yii::$app->user->getId(), 'order_type_code' => ['Affiliate']])->andWhere(['>', 'total', 0])->count("*");
                $order_total = Order::find()->where(['affiliate_id' => \Yii::$app->user->identity->getAffiliateId(), 'order_type_code' => ['Affiliate']])->andWhere(['>', 'total', 0])->andWhere(['not in','order_status_id',[6,7]])->count("*");
            }
            return $this->render('user-share-block',['model'=>$model,'commission_total'=>$commission_total,'follower_total'=>$follower_total,'order_total'=>$order_total]);
        }

	}
}