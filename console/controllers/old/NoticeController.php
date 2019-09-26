<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/21
 * Time: 23:39
 */
namespace console\controllers\old;
use api\models\V1\Category;
use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerCoupon;
use api\models\V1\Order;
use api\models\V1\Point;
use api\models\V1\ProductBase;
use common\component\Message\Msg;
use common\models\User;
use yii\db\Query;
use yii\helpers\Json;
use yii\httpclient\Client;

class NoticeController extends \yii\console\Controller {
	public function actionSms()
	{
		//	print_r($this->getCateChildrenIds(635));
		echo implode(",", $this->getCateChildrenIds(635));
//		$query = CustomerAuthentication::find()->where(['provider'=>'weixin','status'=>1]);
//
//		$product_query=ProductBase::find()->select('product_base_id')->where(['category_id'=>$this->getCateChildrenIds(635)]);
//
//		$order_query=Order::find()->joinWith('order_product')->where(['jr_order.sent_to_erp'=>'Y','op.product_base_id'])
		exit;
	}

	public function getCateChildrenIds($ids, $value = [])
	{
		if (!$value) {
			if (is_array($ids)) {
				$value = $ids;
			} else {
				$value[] = $ids;
			}
		}
		$children = [];
		if ($models = Category::find()->where(['parent_id' => $ids])->all()) {
			foreach ($models as $model) {
				$value[] = $model->category_id;
				$children[] = $model->category_id;
			}
			return $this->getCateChildrenIds($children, $value);
		} else {
			return $value;
		}
	}

	public function actionCoupon()
    {
        $date = date("Y-m-d 23:59:59");
        //24小时之内过期的优惠券
        //$message = [];

//        $query = new Query();
//        $results = $query
//            ->select('cc.customer_id,cc.coupon_id,cc.customer_coupon_id')
//            ->from('jr_customer_coupon cc')
//            ->leftJoin('jr_coupon c','cc.coupon_id = c.coupon_id')
//            ->where(['cc.is_notice'=>'0','cc.is_use'=>0])->andWhere(['and',"unix_timestamp(cc.end_time) - unix_timestamp(NOW()) > 3*60*60"," unix_timestamp(cc.end_time) <= unix_timestamp('".$date."') "])
//            ->andWhere(['and'," cc.coupon_id<>21805 and cc.coupon_id<>22954 and cc.coupon_id<>22975 and cc.coupon_id<>22966 and cc.coupon_id<>22963 and cc.coupon_id<>22948 and cc.coupon_id<>22951 and cc.coupon_id<>22984 and cc.coupon_id<>22957"])
//            ->groupBy('cc.customer_id')
//            ->orderBy('c.discount DESC')
//            ->limit(1000)
//            ->All();
//        echo "---------Start--《" . date('Y-m-d H:i:s') . "》------>\r\n";
        $template_id = "XASlVqkbTJexDOrsWrcl6uY3afDCAZDtnAnFzSkALE4";//道具到期提醒
        $url = 'https://m.mrhuigou.com/user-coupon/index';
        $command = CustomerCoupon::find()->select('customer_id')->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 3*60*60 AND unix_timestamp(end_time) <= unix_timestamp('" . $date . "') AND is_use = 0 and coupon_id<>21805 and coupon_id<>22954 and coupon_id<>22975 and coupon_id<>22966 and coupon_id<>22963 and coupon_id<>22948 and coupon_id<>22951 and coupon_id<>22984 and coupon_id<>22957 and is_notice = 0");
        $group_customer_coupons = $command->groupBy("customer_id")->limit(500)->all();//取出500个有到期提醒的优惠券的客户
        if ($group_customer_coupons) {
            foreach ($group_customer_coupons as $customer) {
                //获取单个用户的待通知优惠券条数
                $customer_coupons = CustomerCoupon::find()->where(['customer_id' => $customer->customer_id, 'is_use' => 0, 'is_notice' => 0])
                    ->andWhere(['and', " unix_timestamp(end_time) - unix_timestamp(NOW()) > 3*60*60 AND unix_timestamp(end_time) <= unix_timestamp('" . $date . "')  and coupon_id<>21805 and coupon_id<>22954 and coupon_id<>22975 and coupon_id<>22966 and coupon_id<>22963 and coupon_id<>22948 and coupon_id<>22951 and coupon_id<>22984 and coupon_id<>22957"])
                    ->all();
                if ($customer_coupons) {

                    $max_coupon = $customer_coupons[0];
                    $max_discount = $max_coupon->coupon->discount;
                    foreach ($customer_coupons as $key => $data) {
                        if ($data->coupon->discount > $max_discount) {
                            $max_coupon = $data;
                        }
                        $data->is_notice = 3;//group通知
                        $data->save();
                    }


                    if ($max_coupon && $user = User::findIdentity($max_coupon->customer_id)) {
                        if ($open_id = $user->getWxOpenId()) {

                            $msg = $this->getMessage("亲，您的道具卡【" . $max_coupon->coupon->name . "】，即将到期，请尽快使用！！！", $max_coupon);
                            $body = [
                                'touser' => $open_id,
                                'template_id' => $template_id,
                                'url' => $url,
                                'topcolor' => '#173177',
                                'data' => $msg
                            ];
                            $result = $this->send($body);
                            echo $max_coupon->customer_coupon_id . "---" . $open_id . "---" . Json::encode($result) . "\r\n";
                            if ($result['errcode'] == 0) {
                                $max_coupon->is_notice = 1;
                                $max_coupon->save();
                            }
                            echo Json::encode($msg) . "\r\n";
                        } else {
                            $max_coupon->is_notice = 2;
                            $max_coupon->save();
                        }
                    }
                }
            }
        }


//        $command = CustomerCoupon::find()->where("unix_timestamp(end_time) - unix_timestamp(NOW()) > 3*60*60 AND unix_timestamp(end_time) <= unix_timestamp('".$date."') AND is_use = 0 and coupon_id<>21805 and coupon_id<>22954 and coupon_id<>22975 and coupon_id<>22966 and coupon_id<>22963 and coupon_id<>22948 and coupon_id<>22951 and coupon_id<>22984 and coupon_id<>22957 and is_notice = 0");
//        echo "---------Start--《" . date('Y-m-d H:i:s') . "》------>\r\n";
//        if ($results = $command->orderBy('customer_id')->limit(1000)->all()) {
//            foreach ($results as $result){
//                $group_customer_coupons[$result->customer_id][] = $result;
//            }
//        }
//		if ($results) {
////            $template_id = "VuG8rmsUhTBX345cyJ44CZv_RLri-7EaKtXM1u9eumM";
//            $template_id = "XASlVqkbTJexDOrsWrcl6uY3afDCAZDtnAnFzSkALE4";//道具到期提醒
//            $url = 'https://m.mrhuigou.com/user-coupon/index';
//
//			foreach ($results as $key => $v) {
//			    $data = CustomerCoupon::findOne(['customer_coupon_id'=>$v['customer_coupon_id']]);
//				if ($user = User::findIdentity($data->customer_id)) {
//					if ($open_id = $user->getWxOpenId()) {
//
//                        $msg = $this->getMessage("亲，您的道具卡【" . $data->coupon->name . "】，即将到期，请尽快使用！！！", $data);
//                        $body = [
//                            'touser' =>$open_id,
//                            'template_id' => $template_id,
//                            'url' => $url,
//                            'topcolor' => '#173177',
//                            'data' => $msg
//                        ];
//                        $result = $this->send($body);
//                        echo $data->customer_coupon_id."----" . $key . "---" . $open_id . "---" . Json::encode($result) . "\r\n";
//                        if($result['errcode'] == 0){
//                            $data->is_notice = 1;
//                            $data->save();
//                        }
//						echo Json::encode($msg)."\r\n";
//					}else{
//                        $data->is_notice = 2;
//                        $data->save();
//                    }
//				}
//			}
//		}

		echo "---------《COMPLETE》------>\r\n";
	}

	private function getMessage($title, $data)
	{
//		$message = [
//			'first' => [
//				'value' => $title,
//				'color' => '#ff0000'
//			],
//			'name' => [
//				'value' => "优惠券",
//				'color' => '#173177'
//			],
//			'expDate' => [
//				'value' => $exp_date,
//				'color' => '#173177'
//			],
//			'remark' => [
//				'value' => $desc."\r\n专注同城，现在下单，今日送达！\r\n如有疑问请联系客服，客服热线4008556977。",
//				'color' => '#173177'
//			]
//		];
        $desc = $data->coupon->comment?$data->coupon->comment:$data->coupon->getDescription();
        $customer = Customer::findOne(['customer_id'=>$data->customer_id]);
        $message = [
            'first' => [
                'value' => $title,
                'color' => '#ff0000'
            ],
            'keyword1' => [
                'value' => $customer->firstname,
                'color' => '#173177'
            ],
            'keyword2' => [
                'value' => $data->coupon->name,
                'color' => '#173177'
            ],
            'keyword3' => [
                'value' => $data->end_time,
                'color' => '#173177'
            ],
            'remark' => [
                'value' => $desc."\r\n专注同城，现在下单，今日送达！\r\n如有疑问请联系客服，客服热线4008556977。",
                'color' => '#173177'
            ]
        ];
		return $message;
	}
	public function actionTemplate($status = "test")
	{
		$template_id = "sv0uTAFGs2DyJ1zplJM0OW4jIgNuSfgfS6yPacy86y8";
		$url = 'https://m.mrhuigou.com/DP0001-501074.html';
		$message = [
			'first' => [
				'value' => '亲：崂山白花蛇草水（玻璃瓶装）330ml*24原价96元，现价86元！',
				'color' => '#ff0000'
			],
			'keyword1' => [
				'value' => "春节爆品活动",
				'color' => '#ff0000'
			],
			'keyword2' => [
				'value' => '1月26日截止，数量有限',
				'color' => '#173177'
			],
			'remark' => [
				'value' => "\r\n满128元送价值68元的意大利葡萄酒一瓶；\r\n专注同城，现在下单，今日送达！\r\n如有疑问请联系客服4008556977！",
				'color' => '#173177'
			]
		];
		echo "---------《" . $status . "》------>\r\n";
		if ($status == 'test') {
			$this->TestCustomer($template_id, $url, $message);
		} else {
			//-----------生产-------------//
			if ($open_ids = $this->getCustomerOpenIds(0,30000)) {
				foreach ($open_ids as $key => $value) {
					$body = [
						'touser' => $value['openid'],
						'template_id' => $template_id,
						'url' => $url,
						'topcolor' => '#173177',
						'data' => $message
					];
					$result = $this->send($body);
					echo count($open_ids)."----" . $key . "---" . $value['openid'] . "---" . Json::encode($result) . "\r\n";
				}
			}
		}
		echo "---------《COMPLETE》------>\r\n";
	}
	public function actionTemplateExp($status = "test")
	{
		$template_id = "5u0WptS6P5y9Iy7Y4L80fysAN0iEqD_xIP0fquFV1ic";
		$url = 'https://m.mrhuigou.com/user-coupon/index';
		$message = [
			'first' => [
				'value' => '亲，你有张【伊利金典立减8元券】优惠券，即将到期，请尽快使用！！！',
				'color' => '#ff0000'
			],
			'keyword1' => [
				'value' => "伊利金典纯牛奶原价68，现价48，用券再减8元",
				'color' => '#173177'
			],
			'keyword2' => [
				'value' => '2017-05-05 23:59:59',
				'color' => '#173177'
			],
			'keyword3' => [
				'value' => "\r\n专注同城，现在下单，今日送达！\r\n除了震撼，我们无法承诺太多，赶快行动吧！\r\n如有疑问请联系客服，客服热线4008556977。",
				'color' => '#173177'
			]
		];
		echo "---------《" . $status . "》------>\r\n";
		if ($status == 'test') {
			$this->TestCustomer($template_id, $url, $message);
		} else {
			//-----------生产-------------//
//			if ($open_ids = $this->getCustomerOpenIds(0, 30000)) {
//				foreach ($open_ids as $key => $value) {
//					$body = [
//						'touser' => $value['openid'],
//						'template_id' => $template_id,
//						'url' => $url,
//						'topcolor' => '#173177',
//						'data' => $message
//					];
//					$result = $this->send($body);
//					echo count($open_ids)."----" . $key . "---" . $value['openid'] . "---" . Json::encode($result) . "\r\n";
//				}
//			}
		}
		echo "---------《COMPLETE》------>\r\n";
	}

	protected function TestCustomer($template_id, $url, $message)
	{
		$open_id = [
			'o0j7XjvizRidrMzK6VK6Xmro0mKA',
			'o0j7XjolNGKNVtw0mYNXTQmllLTA',
			'o0j7XjjieBeLY20pN4rpx5YOefVs',
			'o0j7Xjhqdqi5pm2CIma7LGpUguFg'
		];
		foreach ($open_id as $key => $value) {
			$body = [
				'touser' => $value,
				'template_id' => $template_id,
				'url' => $url,
				'topcolor' => '#173177',
				'data' => $message
			];
			$result = $this->send($body);
			echo "----" . $key . "---" . $value . "---" . Json::encode($result) . "\r\n";
		}
	}

	protected function getCustomerOpenIds($start = 0, $limit = 100)
	{
		$sql = "SELECT openid,customer_id from jr_customer_authentication where customer_id in (SELECT o.customer_id from jr_order_product op LEFT JOIN jr_order o on op.order_id=o.order_id where op.product_base_code in ('501074') and o.sent_to_erp='Y' GROUP BY o.customer_id) and provider='weixin' and `status`=1 GROUP BY openid ORDER BY customer_id DESC  LIMIT " . $start . "," . $limit;
		$command = \Yii::$app->db->createCommand($sql);
		$result = $command->queryAll();
		return $result;
	}

	protected function send($body)
	{
		$result_data="";
		try{
			$token = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $token;
			$http = new Client();
			$http->setTransport('yii\httpclient\CurlTransport');
			$header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
			$response = $http->post($url, Json::encode($body), $header,['sslVERIFYPEER'=>false,'sslVERIFYHOST'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
			if ($response->isOk) {
				$result_data=$response->data;
			}
		} catch (\Exception $e) {
			$result_data="network server error";
		}

		return $result_data;
	}
	public function actionInit()
	{
		if ($openids = $this->getOpenIds()) {
			CustomerAuthentication::updateAll(['status' => 1], ['and', ['in', 'openid', $openids], "provider='weixin'"]);
		}
	}

	public function actionTest()
	{
		$user_list = [];
		if ($openids = $this->getOpenIds()) {
			$pages = [];
			$i = 1;
			foreach ($openids as $key => $value) {
				if ($key > ($i * 100 - 1)) {
					$i++;
				}
				$pages[$i - 1]['user_list'][] = [
					'openid' => $value,
					'lang' => 'zh-CN'
				];
			}
			foreach ($pages as $k => $page) {
				echo "------(" . $k . ")------\r\n";
				$http = new Client();
				$http->setTransport('yii\httpclient\CurlTransport');
				$header = ["Accept" => "application/json", "Content-Type" => "application/json;charset=utf-8"];
				$response = $http->post('https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . $this->getAccessToken(), Json::encode($pages[$k]), $header,['sslVERIFYPEER'=>false,'CONNECTTIMEOUT'=>0,'NOSIGNAL'=>1])->send();
				if ($response->isOk) {
					if (isset($response->data['user_info_list']) && $response->data['user_info_list']) {
						foreach ($response->data['user_info_list'] as $value) {
							$user_list[] = $value;
							echo Json::encode($value)."\r\n";
						}
					}
				}
			}
		}
	}

	public function actionInitUser()
	{
		if ($user_list = $this->getUserList()) {
			$count = count($user_list);
			foreach ($user_list as $key => $value) {
				if (isset($value['unionid']) && $customer_auth = CustomerAuthentication::findOne(['identifier' => $value['unionid']])) {
					if (isset($value['headimgurl'])) {
						Customer::updateAll(['photo' => $value['headimgurl']], ['customer_id' => $customer_auth->customer_id]);
					}
					echo $count . "----" . $key . "\r\n";
				} else {
					echo $count . "----no---" . $key . "\r\n";
				}
			}
		}
	}

	private function getUserList()
	{
		$user_list = [];
		if ($openids = $this->getOpenIds()) {
			$pages = [];
			$i = 1;
			foreach ($openids as $key => $value) {
				if ($key > ($i * 100 - 1)) {
					$i++;
				}
				$pages[$i - 1]['user_list'][] = [
					'openid' => $value,
					'lang' => 'zh-CN'
				];
			}
			foreach ($pages as $k => $page) {
				$http = new Client();
				$http->setTransport('yii\httpclient\CurlTransport');
				$response = $http->post('https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . $this->getAccessToken(), Json::encode($pages[$k]), ['Content-Type' => 'application/json'],['sslVERIFYPEER'=>false,'CONNECTTIMEOUT'=>0])->send();
				if ($response->isOk) {
					if (isset($response->data['user_info_list']) && $response->data['user_info_list']) {
						foreach ($response->data['user_info_list'] as $value) {
							$user_list[] = $value;
						}
					}
				}
			}
		}
		return $user_list;
	}

	private function getOpenIds($open_ids = [], $next_openid = "")
	{
		$http = new Client();
		$http->setTransport('yii\httpclient\CurlTransport');
		$response = $http->get('https://api.weixin.qq.com/cgi-bin/user/get', ['access_token' => $this->getAccessToken(), 'next_openid' => $next_openid],['sslVERIFYPEER'=>false,'CONNECTTIMEOUT'=>0])->send();
		if ($response->isOk) {
			if (isset($response->data['data']) && $response->data['data']['openid']) {
				foreach ($response->data['data']['openid'] as $value) {
					$open_ids[] = $value;
				}
			}
			if (isset($response->data['next_openid']) && $next_openid = $response->data['next_openid']) {
				return $this->getOpenIds($open_ids, $next_openid);
			}
		}
		return $open_ids;
	}

	public function getAccessToken()
	{
		return \Yii::$app->wechat->getAccessToken();
	}
//	public function actionSrwdPoint(){
//	    $point = Point::findOne(['point_id'=>4]);
//	    $point->rate = 0.001429;
//	    $point->save(false);
//	    echo json_encode($point->errors);
//    }
}
