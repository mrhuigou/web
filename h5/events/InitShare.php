<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/10
 * Time: 15:53
 */
namespace h5\events;
use api\models\V1\Affiliate;
use api\models\V1\Customer;
use api\models\V1\Point;
use api\models\V1\PointCustomer;
use yii\base\Event;
class InitShare extends Event{
	public static function assign()
	{
//	    if(\Yii::$app->user->isGuest && \Yii::$app->session->get('from_affiliate_uid')){
//	        \Yii::$app->session->remove('from_affiliate_uid');
//
//        }
//        if(\Yii::$app->user->isGuest && \Yii::$app->session->get('source_from_uid')){
//            \Yii::$app->session->remove('source_from_uid');
//
//        }


//        if(\Yii::$app->user->getId() == '17412'){
//            \Yii::error(\Yii::$app->request->hostInfo());
//        }

        if(strpos(\Yii::$app->request->hostInfo,'mwx.')){

            \Yii::$app->session->set('source_from_agent_wx_xcx',true); //来源微信小程序
        }else{
            if(\Yii::$app->session->get('source_from_agent_wx_xcx')){
                \Yii::$app->session->remove('source_from_agent_wx_xcx');
            } //来源微信小程序
        }
        $session_data = \Yii::$app->session->get('source_from_uid');
		if($follower_id=\Yii::$app->request->get('follower_id')){
            \Yii::$app->session->remove('source_from_uid');
                $data['value'] = $follower_id;
                $data['expire_time'] = time()+3600;
                \Yii::$app->session->set('source_from_uid',$data);
		}else{
		    if($session_data && $session_data['expire_time'] < time()){
                \Yii::$app->session->remove('source_from_uid');
            }
        }
		if($from_affiliate_uid=\Yii::$app->request->get('from_affiliate_uid')){
			\Yii::$app->session->set('from_affiliate_uid',$from_affiliate_uid);
		}
		//第三方分销平台来源与用户token;
		if($from_channel_source=\Yii::$app->request->get('sourcefrom')){
			if($model=Affiliate::findOne(['code'=>$from_channel_source])){
				\Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
			}
		}
        $useragent = \Yii::$app->request->getUserAgent();
        if (strpos(strtolower($useragent), 'zhqdapp')) {
            if($model=Affiliate::findOne(['code'=>'zhqd'])){
                \Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
            }
        }
        if (strpos(strtolower($useragent), 'anfangapp')) {
            if($model=Affiliate::findOne(['code'=>'anfang'])){
                \Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
            }
        }
        if(\Yii::$app->request->get('sourcefrom') == 'mrhuigou' && \Yii::$app->session->has("from_affiliate_uid")){
            \Yii::$app->session->remove('from_affiliate_uid');
        }
//        $path = \Yii::$app->request->getPathInfo();
//        if(  $path != 'site/login' && !\Yii::$app->user->isGuest && \Yii::$app->session->get('from_affiliate_uid') && isset($model)){
//            $user = Customer::findOne(['customer_id'=>\Yii::$app->user->getId()]);
//            if($user->affiliate_id !=  \Yii::$app->session->get('from_affiliate_uid') && \Yii::$app->session->get('from_affiliate_uid') != 259){
////                if($model && $model->point_id){
////                    //$point = Point::findOne(['point_id'=>$aff->point_id]);
////                    $point_customer = PointCustomer::findOne(['customer_id'=>$user->customer_id,'point_id'=>$model->point_id]);
////                    if(!$point_customer){
////                        $point_customer = new PointCustomer();
////                        $point_customer->point_id = $model->point_id;
////                        $point_customer->customer_id = $user->getId();
////                        $point_customer->points = 0;  //消费时候不能直接用该字段
////                        $point_customer->date_added = date("Y-m-d H:i:s");
////                        $point_customer->date_modified = date("Y-m-d H:i:s");
////                        $point_customer->save();
////                    }
////                }
//                \Yii::$app->session->remove('from_affiliate_uid');
//            }
//        }


	}

}