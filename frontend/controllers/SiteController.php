<?php
namespace frontend\controllers;
use api\models\V1\Address;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Affiliate;
use api\models\V1\CategoryDisplay;
use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerFollower;
use api\models\V1\CustomerFootprint;
use api\models\V1\Information;
use common\component\Helper\Helper;
use common\component\Helper\Xcrypt;
use common\component\MapGroups\AddressGroups;
use dosamigos\qrcode\lib\Enum;
use dosamigos\qrcode\QrCode;
use frontend\models\SendCodeForm;
use Yii;
use frontend\models\LoginForm;
use common\models\User;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\captcha\Captcha;
use yii\captcha\CaptchaAction;
use yii\helpers\Url;
use yii\log\Logger;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use api\models\V1\VerifyCode;
use common\component\Message\Sms;
use yii\web\NotFoundHttpException;

/**
 * Site controller
 */
class SiteController extends Controller {
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['get'],
				],
			],
//			[
//				'class' => 'yii\filters\PageCache',
//				'only' => ['index'],
//				'duration' => 60,
//				'variations' => [
//					\Yii::$app->request->getAbsoluteUrl(),
//				]
//			]
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions()
	{
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'backColor' => 0xFFFFFF,  //背景颜色
				'minLength' => 4,  //最短为4位
				'maxLength' => 4,   //是长为4位
				'transparent' => false,  //显示为透明
				'offset' => 2,
				'fixedVerifyCode' => YII_ENV_TEST ? 'test' : null,
			],
			'auth' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'successCallback'],
			],
		];
	}

	public function actionImg()
	{
		if ($product_base_code = Yii::$app->request->get('product_base_code') && $shop_code = Yii::$app->request->get("shop_code")) {
			QrCode::png(Url::to(['product/index', 'product_base_code' => $product_base_code, 'shop_code' => $shop_code], true));
			exit;
		} else {
			return;
		}
	}
	public function actionGetQrcode(){
        $advertise_detail_id = Yii::$app->request->get('id');
        if($advertise_detail_id){
            if($advertise_detail = AdvertiseDetail::findOne(['advertise_detail_id'=>$advertise_detail_id])){
                QrCode::png($advertise_detail->link_url,false,Enum::QR_ECLEVEL_L,7);
            }else{
                return false;
            }
        }
    }

	public function actionIndex()
	{
        //PC-SY-AD1  PC-SY-DES1


       // $silde_position = 'PC-SY-AD1';
		$advertise = new AdvertiseDetail();
		$data['ad1'] = $advertise->getAdvertiserDetailByPositionCode('PC-SY-AD1');
        $data['des1'] = $advertise->getAdvertiserDetailByPositionCode('PC-SY-DES1');

        $this->layout = 'main-remove';
		return $this->render('index',$data);
	}

	public function actionLogin()
	{
		$this->layout = "main-login";
		if (\Yii::$app->request->get('redirect')) {
			$url = \Yii::$app->request->get('redirect');
		} elseif (\Yii::$app->session->get('redirect_url')) {
			$url = \Yii::$app->session->get('redirect_url');
		} else {
			$url = "/site/index";
		}
		if (!\Yii::$app->user->isGuest) {
			return $this->redirect($url);
		}
        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
        if($from_affiliate_uid){
            if($aff = Affiliate::findOne(['affiliate_id'=>$from_affiliate_uid,'status'=>1])){
                $provider = $aff->code;
                $key = "1234567812345678";
                $iv = '1234567812345678';
                $token_encrypt = Yii::$app->request->get('token');
                $crypt = new  Xcrypt($key,'cbc',$iv);
                $token_sring = $crypt->decrypt($token_encrypt);
                $token_arr = json_decode($token_sring);
                if (!empty($token_arr) && !empty($token_arr['telephone']) ) {
                    $token = md5($token_arr['telephone']);
                    if(!$customer = Customer::findOne(['telephone'=>$token_arr['telephone']])){
                        if($customer_auth=CustomerAuthentication::findOne(['identifier' => $token])){
                            $user=User::findIdentity($customer_auth->customer_id);
                        }else {
                            $userInfo['phone'] = $token_arr['telephone'];
                            $userInfo['username'] = '匿名';
                            $userInfo['face'] = '';
                            $user = $this->addUser($userInfo,$provider,$token);
                        }
                    }else{
                        $user=User::findIdentity($customer->customer_id);
                    }

                }else{
                    throw new NotFoundHttpException("数据错误，没有用户标示");
                }
                if($user){
                    Yii::$app->user->login($user);
                    \Yii::$app->cart->loadFromLogin();
                    return $this->redirect($url);
                }else{
                    throw  new NotFoundHttpException('用户信息授权失败');
                }
            }
        }
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			\Yii::$app->cart->loadFromLogin();
			return $this->redirect($url);
		}
		return $this->render('login', [
			'model' => $model,
		]);
	}
    private function addUser($userInfo,$provider,$token){
        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
        if ($userInfo['phone']) {
            if(!$user = User::findByUsername($userInfo['phone'])){
                $user = new User();
                $user->nickname = $userInfo['username'] ? $userInfo['username'] : '匿名';
                $user->firstname = $userInfo['username'] ? $userInfo['username'] : '匿名';
                $user->gender = '未知';
                $user->telephone = $userInfo['phone'];
                $user->photo = $userInfo['face'];
                $user->setPassword('weinxin@365jiarun');
                $user->generateAuthKey();
                $user->email_validate = 0;
                $user->telephone_validate = $userInfo['phone'] ? 1 : 0;
                $user->customer_group_id = 1;
                $user->approved = 1;
                $user->status = 1;
                $user->user_agent = Yii::$app->request->getUserAgent();
                $user->date_added = date('Y-m-d H:i:s', time());
                $user->affiliate_id = $from_affiliate_uid;
                if (!$user->save(false)) {
                    throw new NotFoundHttpException("用户注册失败");
                }
                if ($share_user_id = Yii::$app->session->get('source_from_uid')) {
                    if (User::findIdentity($share_user_id)) {
                        if (!$auth = CustomerFollower::findOne(['follower_id' => $user->getId()])) {
                            $customer_share_user = new CustomerFollower();
                            $customer_share_user->customer_id = $share_user_id;
                            $customer_share_user->follower_id = $user->getId();
                            $customer_share_user->status = 0;
                            $customer_share_user->creat_at = time();
                            $customer_share_user->save();
                        }
                    }
                }
                //用户授权记录
                $model = new CustomerAuthentication();
                $model->provider = $provider;
                $model->customer_id=$user->getId();
                $model->display_name = $userInfo['username'] ? $userInfo['username'] : '匿名';
                $model->gender = '未知';
                $model->phone=$userInfo['phone'];
                $model->photo_url = $userInfo['face'];
                $model->date_added = date('Y-m-d H:i:s', time());
                $model->identifier = $token;
                $model->openid = '';
                $model->status = 0;
                $model->save();
            }

            return $user;
        }else{
            throw new NotFoundHttpException("数据错误，没有用户标示");
        }
    }
	public function actionLogout()
	{
		Yii::$app->user->logout();
		return $this->goHome();
	}

	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending email.');
			}
			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	public function actionAbout()
	{
		return $this->render('about');
	}

	public function actionSignup()
	{
		$this->layout = "main-login";
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		$model = new SignupForm();
		if (!$type = Yii::$app->request->get('type')) {
			$model->setScenario('signup');
		} else {
			$model->setScenario('signupemail');
		}
		if ($model->load(Yii::$app->request->post()) && $user = $model->save()) {
			Yii::$app->getUser()->login($user);
			return $this->goHome();
		}
		return $this->render('signup', [
			'model' => $model,
		]);
	}

	public function actionRequestPasswordReset()
	{
		$this->layout = "main-login";
		$model = new PasswordResetRequestForm();
		if (!$type = Yii::$app->request->get('type')) {
			$model->setScenario('telephone');
		} else {
			$model->setScenario('email');
		}
		if ($model->load(Yii::$app->request->post()) && $user = $model->submit()) {
			if ($type) {
				\Yii::$app->mailer->compose('passwordResetToken', ['user' => $user])
					->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
					->setTo($user->email)
					->setSubject('重置密码')
					->send();
				$url = Helper::gotomail($user->email) ? '//' . Helper::gotomail($user->email) : '/site/index';
				return $this->redirect($url);
			} else {
				return $this->redirect(['/site/reset-password', 'token' => $user->password_reset_token]);
			}
		}
		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		$this->layout = "main-login";
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			return $this->redirect(Url::to(['site/login'], true));
		}
		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	public function actionSendcode()
	{
		$model = new SendCodeForm();
		if (Yii::$app->user->isGuest) {
			$model->setScenario('isGuest');
		} else {
			$model->setScenario('noGuest');
		}
		if ($model->load(['SendCodeForm' => \Yii::$app->request->post()]) && $model->send()) {
			return json_encode(['status' => 1, 'message' => '发送成功']);
		} else {
			return $model->hasErrors() ? json_encode(['status' => 0, 'message' => $model->errors]) : ['status' => 0, 'message' => ['system' => '系统繁忙稍后在试！']];
		}
	}

	public function actionSendcodemail()
	{
		$data = \Yii::$app->request->post();
		if (Yii::$app->session->get('mail_send_limit') > time()) {
			// Yii::$app->session->setFlash('error', 'Too fast!');
			return false;
		}
		if (isset($data['email']) && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $data['email'])) {
			if (!$model = VerifyCode::findOne(['phone' => $data['email'], 'status' => 0])) {
				$code = rand(100000, 999999);
				$model = new VerifyCode();
				$model->phone = strval($data['email']);
				$model->code = strval($code);
				$model->status = 0;
				$model->date_added = date('Y-m-d H:i:s', time());
				$model->save();
			}
			$message = "您的每日惠购验证码:" . $model->code . "，请勿将验证码泄露给其他人。";
			Yii::$app->mailer->compose()
				->setTo($data['email'])
				->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
				->setSubject('每日惠购邮箱验证码')
				->setTextBody('尊敬的会员：' . $message)
				->send();
			\Yii::info($data['email'] . $message);
			Yii::$app->session->set('mail_send_limit', time() + 58);
			return '发送成功';

		} else {
			return '发送失败';
		}
	}

	public function checkcode($phone, $v_code)
	{
		$model = VerifyCode::findOne(['phone' => $phone, 'status' => 0]);
		if (!is_null($model) && $model->code == $v_code) {
			return true;
		} else {
			return false;
		}
	}

	public function actionBackend()
	{
		if ($token = Yii::$app->request->get('token')) {
			if ($user = User::findOne(['auth_key' => $token])) {
				Yii::$app->user->login($user, 3600 * 24);
				return $this->redirect('/account/index');
			}
		}
		return $this->redirect('/site/index');
	}

	/**
	 * Success Callback
	 * @param QqAuth|WeiboAuth $client
	 * @see http://wiki.connect.qq.com/get_user_info
	 * @see http://stuff.cebe.cc/yii2docs/yii-authclient-authaction.html
	 */
	public function successCallback($client)
	{
		$id = $client->getId(); // qq | sina | weixin
		$attributes = $client->getUserAttributes(); // basic info
		$userInfo = $client->getUserInfo(); // user extend info
		$data = [];
		$identifier = '';
		if ($id == "WeiXin") {
			$identifier = $attributes['unionid'];
			$data['nickname'] = $attributes['nickname'];
			$data['sex'] = $attributes['sex'] == 1 ? '男' : ($attributes['sex'] == 2 ? "女" : "保密");
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($attributes['headimgurl'],uniqid().'.jpg');
			$data['openid'] = $attributes['openid'];
		}
		if ($id == "Sina") {
			$identifier = md5($userInfo['idstr']);
			$data['nickname'] = $userInfo['name'];
			$data['sex'] = $userInfo['gender'] == 'm' ? '男' : "女";
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($userInfo['profile_image_url'],uniqid().'.jpg');
			$data['openid'] = md5($userInfo['idstr']);
		}
		if ($id == "QQ") {
			$identifier = $attributes['openid'];
			$data['nickname'] = $userInfo['nickname'];
			$data['sex'] = $userInfo['gender'] == 1 ? '男' : "女";
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($userInfo['figureurl_qq_2'],uniqid().'.jpg');
			$data['openid'] = $attributes['openid'];
		}
		if (!$data) {
			throw new NotFoundHttpException("非法操作！");
		}
		if (!$model = CustomerAuthentication::findOne(['provider' => $id, 'identifier' => [$identifier, md5($identifier)]])) {
			$customer = new User();
			$customer->nickname = $data['nickname'];
			$customer->firstname = $data['nickname'];
			$customer->gender = $data['sex'];
			$customer->photo = $data['headimgurl'];
			$customer->setPassword('weinxin@365jiarun');
			$customer->generateAuthKey();
			$customer->email_validate = 0;
			$customer->telephone_validate = 0;
			$customer->customer_group_id = 1;
			$customer->approved = 1;
			$customer->status = 1;
			$customer->date_added = date('Y-m-d H:i:s', time());
			$customer->user_agent=Yii::$app->request->getUserAgent();
			$customer->save();
			$customer_id = $customer->customer_id;
			$model = new CustomerAuthentication();
			$model->customer_id = $customer_id;
			$model->provider = $id;
			$model->identifier = $identifier;
			$model->openid = $data['openid'];
			$model->display_name = $data['nickname'];
			$model->gender = $data['sex'];
			$model->photo_url = $data['headimgurl'];
			$model->date_added = date('Y-m-d H:i:s', time());
			$model->status = 0;
			if (!$model->save()) {
				Yii::error("weixin 1注册失败！");
			}
		}
		if ($model) {
			$user = User::findIdentity($model->customer_id);
			Yii::$app->user->login($user, 3600 * 24 * 7);
			\Yii::$app->cart->loadFromLogin();
		}
		return $this->redirect('/account/index');
	}

}
