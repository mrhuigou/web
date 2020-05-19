<?php

/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/2/23
 * Time: 10:39
 */
namespace h5\widgets\Block;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\ShareLogoScans;
use common\component\Wx\WxScans;
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
						return $this->render('start',['type'=>$this->type,'status'=>$this->getUserStatus(),'share_logo' => $this->getShareLogo(),'ticket_code' => $this->getTicketCode()]);
//						return $this->render('start',['type'=>$this->type,'status'=>$this->getUserStatus()]);
					}
			}
		}

		//白金每日惠购
        if(strpos(strtolower($useragent), 'micromessenger') && ($from_affiliate_uid == 278) && !\Yii::$app->session->get('source_from_agent_wx_xcx')){
            if (!\Yii::$app->user->isGuest) {
                if(\Yii::$app->user->identity->getSubcription2()){
                    \Yii::$app->session->set('ad_pop_flag2',0);
                    return;
                }else{
                    $time=\Yii::$app->session->get('subcription_time2',0);
                    if((time()-$time)>60*20){
                        \Yii::$app->session->set('subcription_time2',time());
                        \Yii::$app->session->set('subcription_url2',\Yii::$app->request->getAbsoluteUrl());
                    }else{
                        \Yii::$app->session->set('subcription_url2',\Yii::$app->request->getAbsoluteUrl());
                        if(!$this->type){
                            \Yii::$app->session->set('ad_pop_flag2',0);
                            return;
                        }
                    }
                    \Yii::$app->session->set('ad_pop_flag2',1);
                    return $this->render('start',['type'=>$this->type,'status'=>1,'share_logo' => $this->getShareLogo(true),'ticket_code' => $this->getTicketCode(true)]);
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

    //生成相应的场景二维码
	public function getTicketCode($wechat2=false){
        //生成相应的场景二维码
        $scan=new WxScans();
        $scene_str=md5(serialize(time()));
        if($data=$scan->creatScan($scene_str,$wechat2)){
            //创建场景二维码推送的消息
            $ticket_info = array(
                'title' => '恭喜你关注成功!',
                'description' => '点击进入继续操作',
                'pic_url' => 'group1/M00/06/A8/wKgB7l4On3iAO3q1AAIEAGCtyu0922.jpg',
                'url' => \Yii::$app->request->getAbsoluteUrl(),//当前页面的链接
            );
            $ticket = $data['ticket'];
            \Yii::$app->redis->set($ticket,json_encode($ticket_info));
        }
        if($wechat2){
            $url = \Yii::$app->wechat2->getQrCodeUrl($ticket);
            $image = \Yii::$app->wechat2->httpGet($url);//exit;
        }else{
            $url = \Yii::$app->wechat->getQrCodeUrl($ticket);
            $image = \Yii::$app->wechat->httpGet($url);//exit;
        }

//        $url = \Yii::$app->wechat->getQrCodeUrl($ticket);
//        $image = \Yii::$app->wechat->httpGet($url);//exit;
        $file= "./scans_code/".$ticket.".jpg"; //设置图片名字
        file_put_contents($file,$image); //二维码保存到本地
        return "/scans_code/".$ticket.".jpg";
    }

	public function getShareLogo($wechat2=false){
	    if($wechat2){
            $share_logo = '/group1/M00/06/D9/wKgB7l7Dl1GAH-iVAAHY_LIs_UU130.png';
            return $share_logo;
        }
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