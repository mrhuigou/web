<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/4/10
 * Time: 15:53
 */
namespace frontend\events;
use api\models\V1\Affiliate;
use yii\base\Event;
class InitShare extends Event{
	public static function assign()
	{
        $path = \Yii::$app->request->getPathInfo();
        if(!empty($path)){
            $a = (stripos($path,'site/index') === false);
            $b = strpos($path,'information') === false;
            $c = strpos($path,'site/get-qrcode') ===false;
           // $d = strpos($path,'assets') ===false;
            if($a && $b && $c ){
                return \Yii::$app->controller->redirect(['/site/index']);
            }
        }

//	    if(\Yii::$app->user->isGuest && \Yii::$app->session->get('from_affiliate_uid')){
//	        \Yii::$app->session->remove('from_affiliate_uid');
//
//        }
//        if(\Yii::$app->user->isGuest && \Yii::$app->session->get('source_from_uid')){
//            \Yii::$app->session->remove('source_from_uid');
//
//        }
//		if($follower_id=\Yii::$app->request->get('follower_id')){
//			\Yii::$app->session->set('source_from_uid',$follower_id);
//		}
//		if($from_affiliate_uid=\Yii::$app->request->get('from_affiliate_uid')){
//			\Yii::$app->session->set('from_affiliate_uid',$from_affiliate_uid);
//		}
//		//第三方分销平台来源与用户token;
//		if($from_channel_source=\Yii::$app->request->get('sourcefrom')){
//			if($model=Affiliate::findOne(['code'=>$from_channel_source])){
//				\Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
//			}
//		}
//        $useragent = \Yii::$app->request->getUserAgent();
//        if (strpos(strtolower($useragent), 'zhqdapp')) {
//            if($model=Affiliate::findOne(['code'=>'zhqd'])){
//                \Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
//            }
//        }
//        if (strpos(strtolower($useragent), 'anfangapp')) {
//            if($model=Affiliate::findOne(['code'=>'anfang'])){
//                \Yii::$app->session->set('from_affiliate_uid',$model->affiliate_id);
//            }
//        }

	}
}