<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace fx\widgets\Block;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\ShareLogoScans;
use yii\bootstrap\Widget;

class Start extends Widget{
	public $type=0;

	public function init()
	{
		parent::init();
	}
	public function run()
	{
        $from_affiliate_uid=\Yii::$app->session->get('from_affiliate_uid');
		$useragent=\Yii::$app->request->getUserAgent();

		if(strpos(strtolower($useragent), 'micromessenger') && !$from_affiliate_uid && !\Yii::$app->session->get('source_from_agent_wx_xcx')){
			if (!\Yii::$app->user->isGuest) {
					if(\Yii::$app->user->identity->getSubcription()){
						\Yii::$app->session->set('ad_pop_flag',0);
						return;
					}else{
						$time=\Yii::$app->session->get('subcription_time',0);
						if((time()-$time)>60*20){
							\Yii::$app->session->set('subcription_time',time());
							\Yii::$app->session->set('subcription_url',\Yii::$app->request->getAbsoluteUrl());
						}else{
							\Yii::$app->session->set('subcription_url',\Yii::$app->request->getAbsoluteUrl());
							if(!$this->type){
								\Yii::$app->session->set('ad_pop_flag',0);
								return;
							}
						}
						\Yii::$app->session->set('ad_pop_flag',1);
						return $this->render('start',['type'=>$this->type,'status'=>$this->getUserStatus(),'share_logo' => $this->getShareLogo()]);
//						return $this->render('start',['type'=>$this->type,'status'=>$this->getUserStatus()]);
					}
			}
		}
	}
	public function getUserStatus(){
		$status=0;
		if($user_coupon=CustomerCoupon::find()->where(['customer_id'=>\Yii::$app->user->getId(),'coupon_id'=>21676])->all()){
			foreach ($user_coupon as $coupon){

				if($coupon->is_use){
					$status=1;
					break;
				}else{
					if(strtotime($coupon->end_time)>time()){
						$status=1;
						break;
					}
				}
			}
		}
		if($user_order=Order::find()->where(['customer_id'=>\Yii::$app->user->getId()])->andWhere(["or","order_status_id=2","sent_to_erp='Y'"])->count("order_id")){
			$status=1;
		}
		return $status;
	}

	public function getShareLogo(){
        $cur_param=\Yii::$app->request->getPathInfo();

        $logo_type = 1;//默认类型
        $parameter = 0;
        //匹配促销方案
        if(preg_match('/topic\/detail\?code=(?<code>\w+)/', $cur_param,$matches)){
            $logo_type = 2;
            $parameter = $matches['code'];
        };
        //商品详情匹配
        if(preg_match('/(?<shop_code>\w+)-(?<product_code>\w+).html/', $cur_param,$matches)){
            $logo_type = 3;
            $parameter = $matches['product_code'];
        };
        //匹配页面专题
        if(preg_match('/page\/(?<page_id>\w+).html/', $cur_param,$matches)){
            $logo_type = 4;
            $parameter = $matches['page_id'];
        };

//        if($logo_type && $parameter){
            if( $model = ShareLogoScans::findOne(['type' => $logo_type?$logo_type:0 ,'parameter' => $parameter?$parameter:0])){
                if(!empty($model) && $model->logo_url && $model->weixin_scans_id){
                    $share_logo = $model->logo_url;
                }else{
                    $share_logo = '/group1/M00/06/AF/wKgB7l4Vh4aAC6R0AAI4qqO0gas539.png';
                }
            }else{
                $share_logo = '/group1/M00/06/AF/wKgB7l4Vh4aAC6R0AAI4qqO0gas539.png';
            }
//        }else{
//            $share_logo = '/group1/M00/06/AF/wKgB7l4Vh4aAC6R0AAI4qqO0gas539.png';
//        }

        return $share_logo;
    }
}