<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/4/3
 * Time: 14:18
 */
namespace api\controllers;
use api\models\V1\AffiliateCustomer;
use api\models\V1\Coupon;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerFollower;
use api\models\V1\CustomerMap;
use api\models\V1\CustomerSource;
use api\models\V1\Order;
use api\models\V1\PrizeBox;
use api\models\V1\WeixinMessage;
use api\models\V1\WeixinScans;
use api\models\V1\WeixinScansNews;
use api\models\V1\WeixinUserLast;
use common\component\image\Image;
use common\component\Notice\WxNotice;
use common\component\weixin\Wechat;
use common\models\User;
use yii\web\Controller;
use Yii;

class WechatController extends Controller {
	public $msgtype = 'text';   //('text','image','voice','video','shortvideo','link','location','event')
	public $msg = [];

	public function actionIndex()
	{
        file_put_contents('1.txt',11111);
        Yii::trace('start calculating average revenue1111');
        Yii::trace(\Yii::$app->wechat->parseRequestData());
		if ($this->msg = \Yii::$app->wechat->parseRequestData()) {
			$this->msgtype = $this->msg['MsgType'];
			if ($this->msgtype == 'event') {
				$this->responseEvent();
			} else {
				$this->CustomerService();
			}
		} else {
			$this->Valid();
		}
	}

	protected function saveLog()
	{
		if (!$model = WeixinUserLast::findOne(['open_id' => $this->msg['FromUserName']])) {
			$model = new WeixinUserLast();
			$model->open_id = $this->msg['FromUserName'];
		}
		$model->last_at = time();
		$model->save();
	}

	public function responseEvent()
	{
		$Event = strtolower($this->msg['Event']);
		switch ($Event) {
			case 'subscribe':
				$this->Subscribe();
				break;
			case 'unsubscribe':
				$this->Unsubscribe();
				break;
			case 'location':
				$this->Location();
				break;
			case 'click':
				$this->Click();
				break;
			case 'scan':
				$this->Scan();
				break;
			default:
				break;
		}
	}

	public function CustomerService()
	{
		$template = "<xml>";
		$template .= "<ToUserName><![CDATA[" . $this->msg['FromUserName'] . "]]></ToUserName>";
		$template .= "<FromUserName><![CDATA[" . $this->msg['ToUserName'] . "]]></FromUserName>";
		$template .= "<CreateTime>" . time() . "</CreateTime>";
		$template .= "<MsgType><![CDATA[transfer_customer_service]]></MsgType>";
//		$template .= "<TransInfo>";
//		$template .= "<KfAccount><![CDATA[kf2004@qingdaojiarun]]></KfAccount>";
//		$template .= "</TransInfo>";
		$template .= "</xml>";
		echo $template;
		exit;
	}

	public function Click()
	{
		if ($model = WeixinMessage::find()->where(['msgtype' => 'news', 'key' => $this->msg['EventKey']])->orderBy('sort asc')->all()) {
			$data = [];
			foreach ($model as $key => $value) {
				if ($key == 0) {
					$data[] = [
						'title' => $value->title,
						'description' => $value->content,
						'picurl' => Image::resize($value->picurl, 360, 200),
						'url' => $value->url ? $value->url : "https://m.mrhuigou.com/news/detail?id=" . $value->id,
					];
				} else {
					$data[] = [
						'title' => $value->title,
						'description' => $value->content,
						'picurl' => Image::resize($value->picurl, 200, 200),
						'url' => $value->url ? $value->url : "https://m.mrhuigou.com/news/detail?id=" . $value->id,
					];
				}
			}
			\Yii::$app->wechat->sendNews($this->msg['FromUserName'], $data);
		} else {
			\Yii::$app->wechat->sendText($this->msg['FromUserName'], "正在建设中。。。");
		}
	}

	public function Subscribe()
	{
		$openid = $this->msg['FromUserName'];
		$affiliate_id = 0;
		if (isset($this->msg['Ticket']) && $this->msg['Ticket']) {
            $this->getWeinxinScans($this->msg);

		} else {
			if ($model = WeixinMessage::find()->where(['msgtype' => 'news', 'key' => 'subscribe'])->orderBy('sort asc')->all()) {
				$data = [];
				foreach ($model as $key => $value) {
					if ($key == 0) {
						$data[] = [
							'title' => $value->title,
							'description' => $value->content,
							'picurl' => Image::resize($value->picurl, 360, 200),
							'url' => $value->url ? $value->url : "https://m.mrhuigou.com/news/detail?id=" . $value->id,
						];
					} else {
						$data[] = [
							'title' => $value->title,
							'description' => $value->content,
							'picurl' => Image::resize($value->picurl, 200, 200),
							'url' => $value->url ? $value->url : "https://m.mrhuigou.com/news/detail?id=" . $value->id,
						];
					}
				}
				\Yii::$app->wechat->sendNews($this->msg['FromUserName'], $data);
			} else {
				\Yii::$app->wechat->sendText($this->msg['FromUserName'], "恭喜您关注成功！");
			}
			$customer_id = $this->BindUser($openid, $affiliate_id);
			$this->sendGift($customer_id, $openid);
		}
	}
	public function Scan()
	{

		if (isset($this->msg['Ticket']) && $this->msg['Ticket']) {
		    $this->getWeinxinScans($this->msg);
		}
	}
    private function getWeinxinScans($msg){
        $affiliate_id = 0;
        $openid = $msg['FromUserName'];
        if (isset($msg['Ticket']) && $msg['Ticket']) {
            if ($scan = WeixinScans::findOne(['ticket' => $msg['Ticket']])) {
                if ($scan->affiliate) {
                    $affiliate_id = $scan->affiliate->affiliate_id;
                }
                $source_info['status'] = false;
                if($scan->type == 2){
                    $source_info['status'] = true;
                    $source_info['source_from_type'] = $scan->type;
                    $source_info['from_table'] = 'jr_weixin_scans';
                    $source_info['from_id'] = $scan->id;
                    $source_info['code'] = $scan->code;
                }
                if($scan->type == 3){
                    $source_info['status'] = true;
                    $source_info['source_from_type'] = $scan->type;
                    $source_info['from_table'] = 'jr_weixin_scans';
                    $source_info['from_id'] = $scan->id;
                    $source_info['code'] = $scan->code;
                }

                if ($scan_news = WeixinScansNews::find()->where(['weixin_scans_id' => $scan->id])->orderBy('sort_order asc')->all()) {
                    foreach ($scan_news as $k => $scan_new) {

                        if ($k == 0) {
                            $message[] = [
                                'title' => $scan_new->title,
                                'description' => $scan_new->description,
                                'picurl' => Image::resize($scan_new->pic_url, 360, 200),
                                'url' => $scan_new->url,
                            ];
                        } else {
                            $message[] = [
                                'title' => $scan_new->title,
                                'description' => $scan_new->description,
                                'picurl' => Image::resize($scan_new->pic_url, 200, 200),
                                'url' => $scan_new->url,
                            ];
                        }
                    }
                    \Yii::$app->wechat->sendNews($msg['FromUserName'], $message);
                } else {
                    \Yii::$app->wechat->sendText($msg['FromUserName'], "恭喜您关注成功！");
                }
                $customer_id = $this->BindUser($openid, $affiliate_id,$source_info);
                $this->sendGift($customer_id, $openid);
            }
        }
    }

	public function BindUser($openid, $affiliate_id,$source_info=[])
	{
        $is_new_customer = 1;
        $customer_id = 0;
		if ($model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'openid' => $openid])) {
			$model->date_update = date('Y-m-d H:i:s', time());
			$model->status = 1;
			$model->save();
            $is_new_customer = 0;
            $customer_id = $model->customer_id;
		} else {
			if ($UserInfo = \Yii::$app->wechat->getMemberInfo($openid)) {
				$identifier = isset($UserInfo['unionid']) ? $UserInfo['unionid'] : $openid;
				if (!$model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'identifier' => $identifier])) {
					$customer = new User();
					$customer->nickname = $UserInfo['nickname'] ? $UserInfo['nickname'] : '匿名';
					$customer->firstname = $UserInfo['nickname'] ? $UserInfo['nickname'] : '匿名';
					if ($UserInfo['sex'] == 1) {
						$sex = '男';
					} elseif ($UserInfo['sex'] == 2) {
						$sex = '女';
					} else {
						$sex = '未知';
					}
					$customer->gender = $sex;
					$customer->photo = $UserInfo['headimgurl'];
					$customer->setPassword('weinxin@365jiarun');
					$customer->generateAuthKey();
					$customer->email_validate = 0;
					$customer->telephone_validate = 0;
					$customer->customer_group_id = 1;
					$customer->approved = 1;
					$customer->status = 1;
					$customer->date_added = date('Y-m-d H:i:s', time());
					if (!$customer->save(false)) {
						Yii::error("用户注册失败！");
					}
					$customer_id = $customer->customer_id;
					$model = new CustomerAuthentication();
					$model->customer_id = $customer_id;
					$model->provider = 'WeiXin';
					$model->identifier = $identifier;
					$model->openid = $openid;
					$model->display_name = $UserInfo['nickname'] ? $UserInfo['nickname'] : '匿名';
					$model->gender = $sex;
					$model->photo_url = $UserInfo['headimgurl'];
					$model->date_added = date('Y-m-d H:i:s', time());
					$model->status = 1;
					if (!$model->save(false)) {
						Yii::error("关注注册失败！");
					}
				}
			}
		}
        if($source_info && $source_info['status'] && $customer_id){
            $customer_source = CustomerSource::findOne(['customer_id'=>$model->customer_id,'source_from_type'=>$source_info['source_from_type'],'code'=>$source_info['code']]);
            if(!$customer_source){
                $customer_source = new  CustomerSource();
                $customer_source->customer_id = $customer_id ? $customer_id : 0;
                $customer_source->source_from_type = $source_info['source_from_type'];
                $customer_source->source_from_table = $source_info['from_table'];
                $customer_source->source_from_id = $source_info['from_id'];
                $customer_source->code = $source_info['code'];
                $customer_source->date_added = date("Y-m-d H:i:s");
                $customer_source->is_new_customer = $is_new_customer;
                $customer_source->save();
            }
        }
		if (strtotime($model->date_added) > strtotime('2017-03-25')) {
			$this->bindAffiliate($model->customer_id, $affiliate_id);
		}
		return $model->customer_id;
	}

	public function bindAffiliate($customer_id, $affiliate_id)
	{
		if (!$aff_model = AffiliateCustomer::findOne(['customer_id' => $customer_id])) {
			$aff_model = new AffiliateCustomer();
			$aff_model->affiliate_id = $affiliate_id;
			$aff_model->customer_id = $customer_id;
			$aff_model->creat_at = time();
			$aff_model->save();
		}
	}

	public function sendGift($customer_id, $openid)
	{
		if ($coupon_gifts = PrizeBox::find()->where(['status' => 1, 'type' => 'register'])->all()) {
			$msg_tag = 0;
			foreach ($coupon_gifts as $gift) {
				if (!$user_status = $this->getUserStatus($customer_id, $gift->coupon_id)) {
					$customer_coupon = new CustomerCoupon();
					$customer_coupon->customer_id = $customer_id;
					$customer_coupon->coupon_id = $gift->coupon_id;
					$customer_coupon->description = "注册礼券";
					$customer_coupon->is_use = 0;
					if ($gift->coupon->date_type == 'DAYS') {
						$customer_coupon->start_time = date('Y-m-d H:i:s', time());
						$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $gift->coupon->expire_seconds);
					} else {
						$customer_coupon->start_time = $gift->coupon->date_start;
						$customer_coupon->end_time = $gift->coupon->date_end;
					}
					$customer_coupon->date_added = date('Y-m-d H:i:s', time());
					$customer_coupon->save();
					$msg_tag = 1;
				} else {
					break;
				}
			}

			if ($msg_tag) {
			    if($customer_source = CustomerSource::findOne(['customer_id'=>$customer_id,'source_from_type'=>2])){
			        //地推引进用户不发送通知

                }else{
                    $msg = [
                        'title' => "亲，恭喜您获得新会员体验专属券,点击查看",
                        'description' => "",
                        'picurl' => Image::resize("group1/M00/00/6F/wKgB-ForYouACx3nAACgPMNzAS0878.jpg", 360, 200),
                        'url' => 'https://m.mrhuigou.com/user-coupon/index',
                    ];
                    \Yii::$app->wechat->sendNews($openid, [$msg]);
                }
			}
		}
	}

	public function getUserStatus($customer_id, $coupon_id)
	{
		$status = 0;
		if ($user_coupon = CustomerCoupon::find()->where(['customer_id' => $customer_id, 'coupon_id' => $coupon_id])->all()) {
			foreach ($user_coupon as $coupon) {
				if ($coupon->is_use >0) {
					$status = 1;
					break;
				} else {
					if (strtotime($coupon->end_time) > time()) {
						$status = 1;
						break;
					}
				}
			}
		}
		if ($user_order = Order::find()->where(['customer_id' => $customer_id])->andWhere(["or", "order_status_id=2", "sent_to_erp='Y'"])->count("order_id")) {
			$status = 1;
		}
		return $status;
	}

	protected function sendFatherGift($customer_id)
	{
		if ($model = CustomerFollower::findOne(['follower_id' => $customer_id])) {
			if ($coupon_model = Coupon::findOne(['code' => 'ECP170418004'])) {
				$customer_coupon = new CustomerCoupon();
				$customer_coupon->customer_id = $model->customer_id;
				$customer_coupon->coupon_id = $coupon_model->coupon_id;
				$customer_coupon->description = "推荐有礼";
				$customer_coupon->is_use = 0;
				if ($coupon_model->date_type == 'DAYS') {
					$customer_coupon->start_time = date('Y-m-d H:i:s', time());
					$customer_coupon->end_time = date('Y-m-d 23:59:59', time() + $coupon_model->expire_seconds);
				} else {
					$customer_coupon->start_time = $coupon_model->date_start;
					$customer_coupon->end_time = $coupon_model->date_end;
				}
				$customer_coupon->date_added = date('Y-m-d H:i:s', time());
				if (!$customer_coupon->save(false)) {
					Yii::error("推荐有礼:" . json_encode($customer_coupon->errors));
				}
				$message = [
					'title' => '亲，恭喜您获得推荐会员券',
					'total' => '5.5元',
					'exp_date' => '见详情',
				];
				if ($user_model = User::findIdentity($model->customer_id)) {
					if ($openid = $user_model->getWxOpenId()) {
						$wx = new WxNotice();
						$wx->coupon($openid, 'https://m.mrhuigou.com/user-coupon/index', $message);
					}
				}
			}
		}
	}

	public function Unsubscribe()
	{
		$openid = $this->msg['FromUserName'];
		if ($model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'openid' => $openid])) {
			$model->date_update = date('Y-m-d H:i:s', time());
			$model->status = 0;
			$model->save();
		}
	}

	public function Location()
	{
		$openid = $this->msg['FromUserName'];
		if ($model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'openid' => $openid])) {
			if ($model->customer_id) {
				$location = new CustomerMap();
				$location->customer_id = $model->customer_id;
				$location->latitude = $this->msg['Latitude'];
				$location->longitude = $this->msg['Longitude'];
				$location->precision = $this->msg['Precision'];
				$location->data_added = $this->msg['CreateTime'];
				$location->save();
			}
		}
	}

	public function Valid()
	{
		$echoStr = \Yii::$app->request->get('echostr');
		if (\Yii::$app->wechat->checkSignature()) {
			echo $echoStr;
			exit;
		}
	}
}