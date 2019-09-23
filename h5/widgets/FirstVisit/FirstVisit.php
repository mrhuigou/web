<?php
/**
 * Class Widget
 * @author Tsibikov Vitaliy <tsibikov_vit@mail.ru> <tsibikov.com>
 * Create date: 25.02.2015 17:07
 */

namespace h5\widgets\FirstVisit;
use api\models\V1\AdvertiseDetail;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\PrizeBox;
use yii\bootstrap\Widget;
class FirstVisit extends Widget
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
        if(!\Yii::$app->user->isGuest){
		        if(\Yii::$app->redis->HEXISTS('load-page-data',\Yii::$app->user->getId())){
			        $date_time=\Yii::$app->redis->HGET('load-page-data',\Yii::$app->user->getId());
			        if( time()- intval($date_time) <= 60*60*1){
				        \Yii::$app->session->set('pop',false);
				        return;
			        }else{
				        \Yii::$app->redis->Hset('load-page-data', \Yii::$app->user->getId(), time());
			        }
		        }else{
			        \Yii::$app->redis->Hset('load-page-data', \Yii::$app->user->getId(), time());
		        }
		        $advertise = new AdvertiseDetail();
		        $focus_position = ['H5-TC-DES1'];
		        $data = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		        return $this->render('firstvisit',['data'=>$data]);
	        }
    }
}