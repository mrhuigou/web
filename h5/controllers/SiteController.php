<?php
namespace h5\controllers;
use api\models\V1\AdvertiseDetail;
use api\models\V1\Affiliate;
use api\models\V1\Customer;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerFollower;
use api\models\V1\Point;
use api\models\V1\PointCustomer;
use api\models\V1\VerifyCode;
use common\component\Curl\Curl;
use common\component\Helper\AuthState;
use common\component\Helper\Encrypt3des;
use common\component\Helper\Xcrypt;
use common\component\Message\Sms;
use common\component\Payment\WxPay\UnifiedOrder_pub;
use common\component\Payment\WxPay\WxPayConf_pub;
use common\component\Sms\VoiceVerify;
use common\models\User;
use h5\models\AutoLoginForm;
use Yii;
use common\models\LoginForm;
use h5\models\PasswordResetRequestForm;
use h5\models\ResetPasswordForm;
use h5\models\SignuptelForm;
use h5\models\SignupemailForm;
use yii\base\ErrorException;
use yii\base\InvalidParamException;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\httpclient\Client;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
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
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
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
				'height' => 40,
				'minLength' => 4,  //最短为4位
				'maxLength' => 4,   //是长为4位
				'transparent' => false,  //显示为透明
				'offset' => 2,
				// 'fontFile'=>'@h5/web/assets/fonts/38.ttf',
				'fixedVerifyCode' => YII_ENV_TEST ? 'test' : null,
			],
			'auth' => [
				'class' => 'yii\authclient\AuthAction',
				'successCallback' => [$this, 'successCallback'],
			],
		];
	}

	public function actionIndex()
	{
//		if (\Yii::$app->user->isGuest) {
//		return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
//		}
//        $url = "http://m.mrhuigou.com/?sourcefrom=hssrwd&k=1&v=2";
//        $url = $this->removeSourceAff($url);
//        print_r($url);exit;
//        $return = ReturnBase::findOne(['return_id'=>10267]);
//        $this->sendToZhqd($return);exit;
//$this->OrderCommission();
		$data = [];
		$advertise = new AdvertiseDetail();
//		/*获取滚动banner*/
		$focus_position = ['H5-1L-NAV1'];
		$data['nav'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//爆款
		$focus_position = ['H5-0F-AD'];
		$data['hot_sale'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//通栏广告一
		$focus_position = ['H5-TL1-AD'];
		$data['ad_banner_1'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//通栏广告二
		$focus_position = ['H5-TL2-AD'];
		$data['ad_banner_2'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-2F-PROMOTION'];
		$data['ad_promotion'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-2F-PROMOTION1'];
		$data['ad_promotion_12'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//品牌特卖
		$focus_position = ['H5-3F-BRAND'];
		$data['ad_brand'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//楼层广告
		$focus_position = ['H5-1F-SECKILL'];
		$data['firstF_SECKILL'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-1F-FREEDLY'];
		$data['firstF_FREEDLY'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-1F-ACTION'];
		$data['firstF_ACTION'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-1F-PROMOTION'];
		$data['firstF_PROMOTION'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//4F product
		$focus_position = ['H5-4F-PRODUCT-1'];
		$data['fourthF_PRODUCT_ONE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PRODUCT-2'];
		$data['fourthF_PRODUCT_TWO'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PRODUCT-3'];
		$data['fourthF_PRODUCT_THREE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PRODUCT-4'];
		$data['fourthF_PRODUCT_FOUR'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PRODUCT-5'];
		$data['fourthF_PRODUCT_FIVE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-4F-PRODUCT-6'];
        $data['fourthF_PRODUCT_SIX'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//4F brand
		$focus_position = ['H5-4F-PBRAND-1'];
		$data['fourthF_BRAND_ONE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PBRAND-2'];
		$data['fourthF_BRAND_TWO'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PBRAND-3'];
		$data['fourthF_BRAND_THREE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PBRAND-4'];
		$data['fourthF_BRAND_FOUR'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PBRAND-5'];
		$data['fourthF_BRAND_FIVE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-4F-PBRAND-6'];
        $data['fourthF_BRAND_SIX'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		//4F brand logo
		$focus_position = ['H5-4F-PLOGO-1'];
		$data['fourthF_PLOGO_ONE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PLOGO-2'];
		$data['fourthF_PLOGO_TWO'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PLOGO-3'];
		$data['fourthF_PLOGO_THREE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PLOGO-4'];
		$data['fourthF_PLOGO_FOUR'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
		$focus_position = ['H5-4F-PLOGO-5'];
		$data['fourthF_PLOGO_FIVE'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);
        $focus_position = ['H5-4F-PLOGO-6'];
        $data['fourthF_PLOGO_SIX'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);

        $focus_position = ['H5-1L-AD01'];
        $data['firstF_AD01'] = $advertise->getAdvertiserDetailByPositionCode($focus_position);


		return $this->render('index-new', $data);
	}
    public function actionLoginTest(){
        $url = Url::to(["/site/index"],true);
        $state = AuthState::create($url);
        $base_url=Yii::$app->wechat->getOauth2AuthorizeUrl(Url::to(['site/weixin-test'], true),$state,'snsapi_userinfo');
        return $this->redirect($base_url);
    }

	public function actionLogin()
	{
		if (\Yii::$app->request->get('redirect')) {
			$url = \Yii::$app->request->get('redirect');
		} elseif (\Yii::$app->session->get('redirect_url')) {
			$url = \Yii::$app->session->get('redirect_url');
		} else {
			$url = Url::to(["/site/index"],true);
		}

        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
        $useragent = \Yii::$app->request->getUserAgent();
        if(strpos(strtolower($useragent), 'zhqdapp') || strpos(strtolower($useragent), 'anfangapp')){ //APP登录
            if(strpos(strtolower($useragent), 'zhqdapp')){
                $provider = 'Zhqd';
                $key = "ziuppmmve4sb0sv94omuhk1400z95w5d";
                $getUrl = 'http://0532.qingdaonews.com/account/Usercorrelation/login';
                if((isset($_COOKIE['sign']) && isset($_COOKIE['sign_time']) && ($sign = $_COOKIE['sign']) && ($signTime = $_COOKIE['sign_time']))){
                    $md5Str = md5($key . $signTime);
                    $iv = substr($md5Str, 0, 8);
                    $des = new Encrypt3des();
                    $queryParams = $des->decode($sign, $key, $iv);
                    parse_str($queryParams);
                    if (!empty($queryParams) && !empty($token)) {
                        if($customer_auth=CustomerAuthentication::findOne(['provider' => $provider, 'identifier' => $token])){
                            $user=User::findIdentity($customer_auth->customer_id);
                        }else {
                            $client = new Client();
                            $response = $client->createRequest()
                                ->setMethod('get')
                                ->setUrl($getUrl)
                                ->setData(['token' => $token])
                                ->send();
                            if ($response->isOk) {
                                if ($response->data['errcode'] == 0 && ($userInfo = $response->data['data'])) {
                                    $user = $this->addUser($userInfo,$provider,$token);
                                } else {
                                    throw  new NotFoundHttpException('用户授权失败');
                                }
                            }else {
                                throw  new NotFoundHttpException('授权清求失败');
                            }

                        }
                    }
                }else{
                    return $this->render('zhqd-login');
                }
            }elseif (strpos(strtolower($useragent), 'anfangapp')){
                $provider = 'Anfang';
                $key = "71a8bf4eefcae84185fa2fe9b199ae93";
                $getUrl = '';
                $token = Yii::$app->request->get('token');
                $token_string = base64_decode($token);
                $token_arr = explode('|',$token_string);
                $phone = $token_arr[0];
                $token = md5($phone);
                if (!empty($token_string) && !empty($phone) && !empty($token)) {
                    if($customer_auth=CustomerAuthentication::findOne([ 'identifier' => $token])){
                        $user=User::findIdentity($customer_auth->customer_id);
                    }else {
                        $userInfo['phone'] = $phone;
                        $userInfo['username'] = '匿名';
                        $userInfo['face'] = '';
                        $user = $this->addUser($userInfo,$provider,$token);
                    }
                }else{
                    throw new NotFoundHttpException("数据错误，没有用户标示");
                }
            }
            if($user){
                Yii::$app->user->logout();
                Yii::$app->user->login($user);
                \Yii::$app->cart->loadFromLogin();
                return $this->redirect($url);
            }else{
                throw  new NotFoundHttpException('用户信息授权失败');
            }
        }

		if (strpos(strtolower($useragent), 'micromessenger')) {
            $state = AuthState::create($url);
		    if($from_affiliate_uid==259){
                $base_url="http://0532.qingdaonews.com/api/jiarun/wxauth?".http_build_query(['redirect_uri'=>urlencode(Url::to(['/site/auth-zhqd'],true)),'state_ext'=>$state]);
            }else{
                $base_url=Yii::$app->wechat->getOauth2AuthorizeUrl(Url::to(['site/weixin'], true),$state,'snsapi_userinfo');
            }
			return $this->redirect($base_url);
		}else{
            try{
                if($from_affiliate_uid ){
                    if($aff = Affiliate::findOne(['affiliate_id'=>$from_affiliate_uid,'status'=>1])){
                        $provider = $aff->code;
                        $key = $aff->encrypt_key;//"1234567812345678";
                        $iv = $aff->encrypt_iv;// '1234567812345678';
                        $token_encrypt = Yii::$app->request->get('token');
                        $crypt = new  Xcrypt($key,'cbc',$iv);
                        $token_string = $crypt->decrypt($token_encrypt);
                        $token_arr = json_decode($token_string);
                        if (!empty($token_arr) && !empty($token_arr->telephone) ) {
                            $token = md5($token_arr->telephone);
                            if($customer = Customer::findOne(['telephone'=>$token_arr->telephone])){
                                $user=User::findIdentity($customer->customer_id);
                                if($customer->affiliate_id !=  Yii::$app->session->get('from_affiliate_uid')){
                                    $url = $this->removeSourceAff($url);
                                }
                            }else{
                                if($customer_auth=CustomerAuthentication::findOne(['identifier' => $token])){
                                    $user=User::findIdentity($customer_auth->customer_id);
                                    if($user->affiliate_id !=  Yii::$app->session->get('from_affiliate_uid')){
                                        $url = $this->removeSourceAff($url);
                                    }
                                }else {
                                    $userInfo['phone'] = $token_arr->telephone;
                                    $userInfo['username'] = '匿名';
                                    $userInfo['face'] = '';
                                    $user = $this->addUser($userInfo,$provider,$token);
                                }
                            }

                        }else{

                            throw new NotFoundHttpException("数据错误，没有用户标示");
                        }
                        if($user){
                            if($aff->point_id){ //接入商有积分
                                $point_customer = PointCustomer::findOne(['customer_id'=>$user->getId(),'point_id'=>$aff->point_id]);
                                if(!$point_customer){
                                        //只有不存在 point_customer时才会新增point_customer
                                        //$points = $point_customer->point->pointByCurl; //实时获取points 消费时候不能
                                        $point_customer = new PointCustomer();
                                        $point_customer->point_id = $aff->point_id;
                                        $point_customer->customer_id = $user->getId();
                                        $point_customer->points = 0;  //消费时候不能直接用该字段
                                        $point_customer->date_added = date("Y-m-d H:i:s");
                                        $point_customer->date_modified = date("Y-m-d H:i:s");
                                        $point_customer->save();
                                }
                            }
                            Yii::$app->user->logout();
                            Yii::$app->user->login($user);
                            \Yii::$app->cart->loadFromLogin();
                            return $this->redirect($url);
                        }else{
                            throw  new NotFoundHttpException('用户信息授权失败');
                        }
                    }
                }
            }catch (NotFoundHttpException $e){
                Yii::$app->session->remove("from_affiliate_uid");
                Yii::error($e->getMessage().''.$e->getLine());
            }

        }
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect($url);
        }
		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			\Yii::$app->cart->loadFromLogin();
			return $this->redirect($url);
		} else {
			return $this->render('login', [
				'model' => $model,
			]);
		}
	}
	private function removeSourceAff($url){
        $url_params = "";
        $part = parse_url($url);
      //  print_r($part['query']);
        parse_str($part['query'],$url_params);
        $p_arr = [];

        foreach ($url_params as $key => $url_param){
            if($key != 'sourcefrom'){
                $p_arr[$key] = $url_param;
            }
        }
        if(count($p_arr)>0){
            $p_string = http_build_query($p_arr);
            $url = $part['scheme'].'://'.$part['host'].$part['path'].'?'.$p_string;
        }
        Yii::$app->session->remove('from_affiliate_uid');
        return $url;
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
        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
		Yii::$app->user->logout();
        if($from_affiliate_uid){
            \Yii::$app->session->set('from_affiliate_uid',$from_affiliate_uid);
        }
		return $this->goHome();
	}

	public function actionAbout()
	{
		return $this->render('about');
	}

	public function actionSignup()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		$useragent = \Yii::$app->request->getUserAgent();
		if (strpos(strtolower($useragent), 'micromessenger')) {
			$state = AuthState::create($url);
			$base_url=Yii::$app->wechat->getOauth2AuthorizeUrl(Url::to(['site/weixin'], true),$state,'snsapi_userinfo');
			return $this->redirect($base_url);
		}
		$type = Yii::$app->request->get('type');
		if ($type == "email") {
			$model = new SignupemailForm();
		} else {
			$model = new SignuptelForm();
		}
		if ($model->load(Yii::$app->request->post())) {
			if ($user = $model->signup()) {
				Yii::$app->getUser()->login($user);
				\Yii::$app->cart->loadFromLogin();
				return $this->redirect($url);
			}
		}
		return $this->render('signup', [
			'model' => $model,
			'type' => $type
		]);
	}

	public function actionSendVoice()
	{
		if ($telephone = Yii::$app->request->post('telephone')) {
			if ($model = VerifyCode::findOne(['phone' => $telephone, 'status' => 0])) {
				$model->status = 1;
				$model->save();
			}
			$code = rand(1000, 9999);
			$model = new VerifyCode();
			$model->phone = $telephone;
			$model->code = strval($code);
			$model->status = 0;
			$model->date_added = date('Y-m-d H:i:s', time());
			$model->save();
			$voice = new VoiceVerify();
			$voice->send($telephone, $code);
		}
	}

	public function actionSendcode()
	{

        if (Yii::$app->session->get('telephone_send_limit') > time()) {
            Yii::$app->session->setFlash('error', '发送频率太高，请稍后再试');
            $msg = '发送频率太高，请稍后再试';
            $result['msg'] = $msg;
            $result['status'] = false;
            return json_encode($result);
            //return false;
        }
		if ($telephone = Yii::$app->request->post('telephone')) {
			if ($model = VerifyCode::findOne(['phone' => $telephone, 'status' => 0])) {
				$model->status = 1;
				$model->save();
			}
			$code = rand(1000, 9999);
			$model = new VerifyCode();
			$model->phone = $telephone;
			$model->code = strval($code);
			$model->status = 0;
			$model->date_added = date('Y-m-d H:i:s', time());
			$model->save();
			$message = "您的每日惠购验证码:" . $model->code . "，请勿将验证码泄露给其他人。";
//			Sms::send($telephone,$message);
//            $re = Sms::send_system($telephone,$message);
//            print_r($re);
			$voice = new VoiceVerify();
            $re = $voice->send($telephone, $code);
            Yii::$app->session->set('telephone_send_limit', time() + 58);
            $msg = '发送成功';
            $status = true;
            $result['msg'] = $msg;
            $result['status'] = $status;
            return json_encode($result);
		}

	}

	public function actionSendcodemail()
	{
		$data = \Yii::$app->request->post();
		if (Yii::$app->session->get('mail_send_limit') > time()) {
			Yii::$app->session->setFlash('error', 'Too fast!');
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

	public function actionRequestPasswordReset()
	{
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			$user = User::findOne([
				'telephone' => $model->telephone,
			]);
			if ($user) {
				if (!User::isPasswordResetTokenValid($user->password_reset_token)) {
					$user->generatePasswordResetToken();
				}
				if ($user->save()) {
					$url = Url::to(['site/reset-password', 'token' => $user->password_reset_token], true);
					return $this->redirect($url);
				}
			} else {
				Yii::error('Sorry, 非法操作！.');
			}
		}
		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	public function actionResetPassword($token)
	{
		try {
			$model = new ResetPasswordForm($token);
		} catch (InvalidParamException $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			// Yii::$app->getSession()->setFlash('success', 'New password was saved.');
			return $this->redirect(Url::to(['site/login'], true));
		}
		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	public function actionAutoLogin()
	{
        Yii::error('actionAutoLogin:');
		$id = Yii::$app->request->get('auth_id');
		$redirect = Yii::$app->request->get('redirect');
		if (!$authentication_model = CustomerAuthentication::findOne($id)) {
			throw new NotFoundHttpException("参数错误");
		}
		$model = new AutoLoginForm($authentication_model);
        Yii::error('source_from_begin:$code:'.json_encode($model));
		if ($model->load(Yii::$app->request->post()) && $user = $model->save()) {
			$user = User::findIdentity($user->customer_id);
			Yii::$app->user->login($user, 3600 * 24 * 7);
			\Yii::$app->cart->loadFromLogin();
			if ($redirect) {
				return $this->redirect($redirect);
			} else {
				return $this->redirect("/user/index");
			}
		}
		return $this->render('auto-login', ['authentication_model' => $authentication_model, 'model' => $model]);
	}
    public function actionAuthZhqd(){
        Yii::error('action AuthZhqd:'.json_encode(Yii::$app->request->get()));
        $token = Yii::$app->request->get("token");
        $state_ext = AuthState::get(Yii::$app->request->get("state_ext"))."|".$token;
        $useragent = \Yii::$app->request->getUserAgent();
        if (strpos(strtolower($useragent), 'micromessenger')) {
            $state = AuthState::create($state_ext);
            $base_url=Yii::$app->wechat->getOauth2AuthorizeUrl(Url::to(['site/weixin-base'], true),$state,'snsapi_base');//每日惠购静默授权
            Yii::error('base_url:',json_encode($base_url));
            return $this->redirect($base_url);
        }
    }
    public function actionWeixinBase(){
        $code = Yii::$app->request->get("code");
        Yii::error('action WeixinBase:'.json_encode(Yii::$app->request->get()));
        $state = AuthState::get(Yii::$app->request->get("state"));
        list($url,$token)=explode("|",$state);
        $from_affiliate_uid=Yii::$app->session->get('from_affiliate_uid');
        if ($result = Yii::$app->wechat->getOauth2AccessToken($code, $grantType = 'authorization_code')) {
            $identifier = isset($result['unionid']) ? $result['unionid'] : $result['openid'];
            //-- 使用token获取用户信息
            if($customer_auth=CustomerAuthentication::findOne(['provider' => 'WeiXin', 'identifier' => $identifier])){
                $user=User::findIdentity($customer_auth->customer_id);
            }else {
                $client = new Client();
                $response = $client->createRequest()
                    ->setMethod('get')
                    ->setUrl('http://0532.qingdaonews.com/account/Usercorrelation/login')
                    ->setData(['token' => $token])
                    ->send();
                if ($response->isOk) {
                    if ($response->data['errcode'] == 0 && ($userInfo = $response->data['data'])) {
                        if ($userInfo['phone'] && (!$user = User::findByUsername($userInfo['phone']))) {
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
                            $model->provider = 'WeiXin';
                            $model->customer_id=$user->getId();
                            $model->display_name = $userInfo['username'] ? $userInfo['username'] : '匿名';
                            $model->gender = '未知';
                            $model->phone=$userInfo['phone'];
                            $model->photo_url = $userInfo['face'];
                            $model->date_added = date('Y-m-d H:i:s', time());
                            $model->identifier = $identifier;
                            $model->openid = isset($result['openid'])?$result['openid']:'';
                            $model->status = 0;
                            $model->save();
                        }
                    } else {
                        throw  new NotFoundHttpException('用户授权失败');
                    }
                } else {
                    throw  new NotFoundHttpException('授权清求失败');
                }
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
    public function actionWeixinTest()
    {
        $code = Yii::$app->request->get("code");
        $state = AuthState::get(Yii::$app->request->get("state"));
        Yii::error('source_from_begin:$code:'.json_encode($code));
        $result = Yii::$app->wechat->getOauth2AccessTokenTest($code, $grantType = 'authorization_code');
        Yii::error('source_from_begin:$result:'.json_encode($result));
        if($ret = isset($result['errmsg']) ? false : $result){
            $UserInfo = Yii::$app->wechat->getSnsMemberInfoTest($result['openid'], $result['access_token']);
            Yii::error('source_from_begin:$UserInfo:'.json_encode($UserInfo));
        }
    }

	public function actionWeixin()
	{
		$code = Yii::$app->request->get("code");
		$state = AuthState::get(Yii::$app->request->get("state"));
        Yii::error('source_from_begin:$state:'.$state);
        Yii::error('source_from_begin:$code:'.json_encode($code));
        Yii::error('source_from_begin:time1:'.time());
		if ($result = Yii::$app->wechat->getOauth2AccessToken($code, $grantType = 'authorization_code')) {
            Yii::error('source_from_begin:$result_time:'.time());
            Yii::error('source_from_begin:$result:'.json_encode($result));
			$identifier = isset($result['unionid']) ? $result['unionid'] : $result['openid'];
            Yii::error('source_from_begin:openid:'.$result['openid']);
            Yii::error('source_from_begin:access_token:'.$result['access_token']);
            Yii::error('source_from_begin:$model_time1:'.time());
			if (!$model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'identifier' => [$identifier, md5($identifier)]])) {
//			if (!$model = CustomerAuthentication::findOne(['provider' => 'WeiXin', 'identifier' => $identifier])) {
                Yii::error('source_from_begin:$model_time2:'.time());
				$model = new CustomerAuthentication();
				$model->status = 0;
				$model->date_added = date('Y-m-d H:i:s', time());
			}
            Yii::error('source_from_begin:$customerAuthentication:'.json_encode($model));
            Yii::error('source_from_begin:$UserInfo_time1:'.time());
			if ($UserInfo = Yii::$app->wechat->getSnsMemberInfo($result['openid'], $result['access_token'])) {
                Yii::error('source_from_begin:$UserInfol_time2:'.time());
                Yii::error('source_from_begin:$UserInfo:'.json_encode($UserInfo));
				if ($UserInfo['sex'] == 1) {
					$sex = '男';
				} elseif ($UserInfo['sex'] == 2) {
					$sex = '女';
				} else {
					$sex = '未知';
				}
				$model->provider = 'WeiXin';
				$model->display_name = $UserInfo['nickname'] ? $UserInfo['nickname'] : "匿名";
				$model->gender = $sex;
				$model->photo_url = $UserInfo['headimgurl'];
				$model->date_added = date('Y-m-d H:i:s', time());
				$model->identifier = $identifier;
				$model->openid = $UserInfo['openid'];
				$model->save();
			} else {
				throw new NotFoundHttpException("获取用户信息失败");
			}
            Yii::error('source_from_begin:customer_id:'.$model->customer_id);
            Yii::error('source_from_begin:$customer:'.json_encode(User::findIdentity($model->customer_id)));
			if (!$customer = User::findIdentity($model->customer_id)) {
				$customer = new User();
				$customer->nickname = $model->display_name;
				$customer->firstname = $model->display_name;
				$customer->gender = $model->gender;
				$customer->photo = $model->photo_url;
				$customer->setPassword('weinxin@365jiarun');
				$customer->generateAuthKey();
				$customer->email_validate = 0;
				$customer->telephone_validate = 0;
				$customer->customer_group_id = 1;
				$customer->approved = 1;
				$customer->status = 1;
				$customer->user_agent = Yii::$app->request->getUserAgent();
				$customer->date_added = date('Y-m-d H:i:s', time());
                if(Yii::$app->session->get('from_affiliate_uid')){
                    $customer->affiliate_id = Yii::$app->session->get('from_affiliate_uid');
                }
				if (!$customer->save(false)) {
					throw new NotFoundHttpException("用户注册失败");
				}
				if ($auth = CustomerAuthentication::findOne(['customer_authentication_id' => $model->customer_authentication_id])) {
					$auth->customer_id = $customer->customer_id;
					if (!$auth->save(false)) {
						throw new NotFoundHttpException("用户绑定微信失败");
					}
				}
                Yii::error('source_from_begin:'.Yii::$app->session->get('source_from_uid'));
				if ($share_user_id = Yii::$app->session->get('source_from_uid')) {
                    Yii::error('source_from_begin,$share_user:'.json_encode(User::findIdentity($share_user_id)));
					if (User::findIdentity($share_user_id)) {

						if (!$auth = CustomerFollower::findOne(['follower_id' => $customer->getId()])) {
							$customer_share_user = new CustomerFollower();
							$customer_share_user->customer_id = $share_user_id;
							$customer_share_user->follower_id = $customer->getId();
							$customer_share_user->status = 0;
							$customer_share_user->creat_at = time();
							$customer_share_user->save();
                            Yii::error('source_from_begin,$customer_share_user:'.json_encode($customer_share_user));
						}
					}
				}
			}
			if(Yii::$app->session->get('from_affiliate_uid')){
			    $aff = Affiliate::findOne(['affiliate_id'=>Yii::$app->session->get('from_affiliate_uid')]);
                if($aff->point_id){ //接入商有积分
                    $point_customer = PointCustomer::findOne(['customer_id'=>$customer->getId(),'point_id'=>$aff->point_id]);
                    if(!$point_customer){
                        //只有不存在 point_customer时才会新增point_customer
                        //$points = $point_customer->point->pointByCurl; //实时获取points 消费时候不能
                        $point_customer = new PointCustomer();
                        $point_customer->point_id = $aff->point_id;
                        $point_customer->customer_id = $customer->getId();
                        $point_customer->points = 0;  //消费时候不能直接用该字段
                        $point_customer->date_added = date("Y-m-d H:i:s");
                        $point_customer->date_modified = date("Y-m-d H:i:s");
                        $point_customer->save();
                    }
                }
            }
            Yii::error('source_from_begin:time2:'.time());
			Yii::$app->user->login($customer, 3600 * 24 * 7);
			\Yii::$app->cart->loadFromLogin();
            Yii::error('source_from_begin:time3:'.time());
			return $this->redirect($state);
		} else {
			throw new NotFoundHttpException("用户授权失败！");
		}
	}

	/**
	 * Success Callback
	 * @param QqAuth|WeiboAuth $client
	 * @see http://wiki.connect.qq.com/get_user_info
	 * @see http://stuff.cebe.cc/yii2docs/yii-authclient-authaction.html
	 */
	public function successCallback($client)
	{
        Yii::error('successCallback:');
		$id = $client->getId(); // qq | sina | weixin
		$attributes = $client->getUserAttributes(); // basic info
		$userInfo = $client->getUserInfo(); // user extend info
		$data = [];
		$identifier = '';
		if ($id == "WeiXin") {
			$identifier = $attributes['unionid'];
			$data['nickname'] = $attributes['nickname'];
			$data['sex'] = $attributes['sex'] == 1 ? '男' : ($attributes['sex'] == 2 ? "女" : "保密");
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($attributes['headimgurl'], uniqid() . '.jpg');
			$data['openid'] = $attributes['openid'];
		}
		if ($id == "Sina") {
			$identifier = md5($userInfo['idstr']);
			$data['nickname'] = $userInfo['name'];
			$data['sex'] = $userInfo['gender'] == 'm' ? '男' : "女";
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($userInfo['profile_image_url'], uniqid() . '.jpg');
			$data['openid'] = md5($userInfo['idstr']);
		}
		if ($id == "QQ") {
			$identifier = $attributes['openid'];
			$data['nickname'] = $userInfo['nickname'];
			$data['sex'] = $userInfo['gender'] == 1 ? '男' : "女";
			$data['headimgurl'] = \common\component\Helper\Image::UploadFormUrl($userInfo['figureurl_qq_2'], uniqid() . '.jpg');
			$data['openid'] = $attributes['openid'];
		}
		if (!$data) {
			throw new NotFoundHttpException("非法操作！");
		}
		if (!$model = CustomerAuthentication::findOne(['provider' => $id, 'identifier' => [$identifier, md5($identifier)]])) {
			$model = new CustomerAuthentication();
			$model->provider = $id;
			$model->identifier = $identifier;
			$model->status = 0;
			$model->openid = $data['openid'];
			$model->date_added = date('Y-m-d H:i:s', time());
		}
		$model->display_name = $data['nickname'];
		$model->gender = $data['sex'];
		$model->photo_url = $data['headimgurl'];
		if (!$model->save()) {
			throw new NotFoundHttpException("用户授权失败！");
		}
		if ($model->customer_id) {
			$user = User::findIdentity($model->customer_id);
			if ($user->telephone_validate) {
				Yii::$app->user->login($user, 3600 * 24 * 7);
				\Yii::$app->cart->loadFromLogin();
				return $this->redirect("/user/index");
			} else {
				return $this->redirect(['/site/auto-login', 'auth_id' => $model->customer_authentication_id, 'redirect' => '/site/index']);
			}
		} else {
			return $this->redirect(['/site/auto-login', 'auth_id' => $model->customer_authentication_id, 'redirect' => '/site/index']);
		}
	}
	public function actionGoTo()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }

        $code = Yii::$app->request->get('code');
        if ($aff = Affiliate::findOne(['code' => $code, 'status' => 1])) {
            $user = Customer::findOne(['customer_id'=>Yii::$app->user->getId()]);
            if($aff->point_id){
                $point_model = Point::findOne(['point_id'=>$aff->point_id]);
                if($point_model){
                    $point_customer = PointCustomer::findOne(['customer_id'=>Yii::$app->user->getId(),'point_id'=>$point_model->point_id]);
                    if(!$point_customer){
                        return $this->render('/site/sunraising-signup');//
                    }
                }
            }
            if (isset($user->telephone) && !empty($user->telephone)) {
                $key = $aff->encrypt_key;//"1234567812345678";
                $iv = $aff->encrypt_iv;// '1234567812345678';
                $crypt = new  Xcrypt($key, 'cbc', $iv);
                $token['telephone'] = $user->telephone;
                list($msec, $sec) = explode(' ', microtime());
                $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
                $token['t'] = $msectime;

                $token_en = $crypt->encrypt(json_encode($token));
                if($url = $aff->login_url){
                    $url = $url.'&token='.urlencode($token_en).'&t='.$token['t'];

                    return $this->redirect($url);
                }else{
                    return $this->redirect(['site/index']);
                }

            }else{
                return $this->redirect(['user/security-set-telephone','redirect'=>Yii::$app->request->getAbsoluteUrl()]);
            }


        }

    }
    public function actionSunraisingSignup(){

        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => \Yii::$app->request->getAbsoluteUrl()]);
        }
//        if(Yii::$app->request->get('redirect')){
//            $redirect = Yii::$app->request->get('redirect');
//        }
        if (Yii::$app->request->isPost && Yii::$app->request->post('sign')) {
            $aff = Affiliate::findOne(['code' => 'hssrwd', 'status' => 1]);
            $result['status'] = false;
            if ($aff->point_id) {
                $result['status'] = true;
                $result['redirect'] = Url::to(['/site/go-to','code'=>$aff->code],true);
                $point_model = Point::findOne(['point_id' => $aff->point_id]);
                if($point_model){
                    $point_customer = PointCustomer::findOne(['customer_id'=>Yii::$app->user->getId(),'point_id'=>$point_model->point_id]);
                    if(!$point_customer){
                        $point_customer = new  PointCustomer();
                        $point_customer->point_id = $point_model->point_id;
                        $point_customer->customer_id = Yii::$app->user->getId();
                        $point_customer->points = 0;
                        $point_customer->date_added = date('Y-m-d H:i:s');
                        $point_customer->date_modified = date('Y-m-d H:i:s');
                        $point_customer->save(false);
                        if(!$point_customer->hasErrors()){
                            $result['status'] = true;
                        }
                    }
                }
            }

            return json_encode($result);
            //return $this->redirect(['/site/go-to','code'=>$aff->code]);

        }
        return $this->render('site/sunraising-signup');

    }
    public function actionGetReceiveCode(){
        $order_id = Yii::$app->request->get('order_id');
        $xcrypt = new Xcrypt('qwertyu8','ecb');
        $en_code = $xcrypt->encrypt($order_id,'hex');
        echo $en_code;exit;
    }
    public function actionBackend()
    {
        if ($token = Yii::$app->request->get('token')) {
            if ($user = User::findOne(['auth_key' => $token])) {
                Yii::$app->user->logout();
                Yii::$app->user->login($user, 3600 * 24);
                return $this->redirect('/user/index');
            }
        }
        return $this->redirect('/site/index');
    }
    public function actionShowStock(){
        if ($token = Yii::$app->request->get('token')) {
            if ($user = \api\models\V1\User::findOne(['show_stock_token' => $token])) {
                Yii::$app->session->set('ShowStock',true);
                $user->show_stock_token = '';
                $user->save();
                return $this->redirect('/site/index');
            }
        }
        return $this->redirect('/site/index');
    }
    public function actionTestWx(){
        //测试服务器 是否支持 微信支付新更换的ssl证书
        $post_url = 'https://apitest.mch.weixin.qq.com/sandboxnew/pay/getsignkey';
        $unifiedOrder = new UnifiedOrder_pub();
        $data['mch_id'] = WxPayConf_pub::MCHID;
        $data['nonce_str'] = $unifiedOrder->createNoncestr();
        $data['sign'] = $unifiedOrder->getSign($data);
        $xml = $unifiedOrder->arrayToXml($data);
        $res = $unifiedOrder->postXmlCurl($xml,$post_url);
        $res_arr = $unifiedOrder->xmlToArray($res);

        print_r($res_arr);
        print_r(Yii::$app->request->userAgent);
        exit;
    }
}
