<?php
namespace h5\controllers;
use api\models\V1\Customer;
use api\models\V1\CustomerAffiliate;
use api\models\V1\CustomerAuthentication;
use api\models\V1\CustomerTransaction;
use api\models\V1\Order;
use api\models\V1\VerifyCode;
use common\component\image\Image;
use common\component\Wx\WxSdk;
use common\models\LoginForm;
use common\models\SecuritySetEmailForm;
use common\models\SecuritySetPasswordForm;
use common\models\SecuritySetPaymentPwdForm;
use common\models\SecuritySetTelephoneForm;
use common\models\SecurityValidateTelephoneForm;
use common\models\User;
use dosamigos\qrcode\QrCode;
use frontend\models\AuthenticationForm;
use frontend\models\ResetPasswordForm;
use h5\models\RealNameForm;
use h5\models\TelephoneBindForm;
use yii\base\ErrorException;
use yii\base\Exception;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;

class UserController extends \yii\web\Controller {
	public function actions(){
		return [
			'wx-js-call' => [
				'class' => 'common\component\Payment\WxPay\WxpayAction',
			],
		];
	}
	public function actionIndex()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = Order::find()->where(['customer_id' => \Yii::$app->user->getId(), 'order_type_code' => ['normal', 'presell']]);
		$nopay = $model->andWhere(['order_status_id' => 1])->count('*');
		$model = Order::find()->where(['customer_id' => \Yii::$app->user->getId(), 'order_type_code' => ['normal', 'presell']]);
		$noway = $model->andWhere(['order_status_id' => [2, 3, 5]])->count('*');
		$model = Order::find()->where(['customer_id' => \Yii::$app->user->getId(), 'order_type_code' => ['normal', 'presell']]);
		$onway = $model->andWhere(['order_status_id' => 9])->count('*');
		return $this->render('index', ['nopay' => $nopay, 'noway' => $noway, 'onway' => $onway,]);
	}
	public function actionBindTelephone(){
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$useragent=\Yii::$app->request->getUserAgent();
		if(strpos(strtolower($useragent), 'micromessenger') && !$open_id=\Yii::$app->session->get('open_id')){
			return $this->redirect(['/user/wx-js-call','path'=>Url::to(['/user/bind-telephone','redirect'=>$url],true)]);
		}
		if(isset($open_id) && $open_id){
			$auth=CustomerAuthentication::findOne(['openid'=>$open_id,'provider'=>'WeiXin']);
			$model=new TelephoneBindForm($auth);
		}else{
			$model=new TelephoneBindForm();
		}
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect($url);
		}
		return $this->render('bind-telephone', [
			'model' => $model,
		]);
	}
	public function actionBind()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		if(!$union_id=\Yii::$app->session->get('union_id')){
			return $this->redirect(['/user/wx-js-call','path'=>Url::to(['/user/bind'],true)]);
		}
		$model = new LoginForm();
		if ($model->load(\Yii::$app->request->post()) && $model->login()) {
			if($auth=CustomerAuthentication::findOne(['provider'=>'WeiXin','identifier'=>$union_id])){
				$auth->customer_id=\Yii::$app->user->getId();
				$auth->save();
			}
			return $this->redirect($url);
		} else {
			return $this->render('bind', [
				'model' => $model,
			]);
		}
	}

	public function actionAvatar()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = User::findOne(\Yii::$app->user->getId());
		if (\Yii::$app->request->isAjax && \Yii::$app->request->post()) {
			$data = [];
			try {
				if (!$img_content = \Yii::$app->request->post('data')) {
					throw new ErrorException('没有上传图片');
				}
				if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $img_content, $result)) {
					$type = $result[2];
					$new_file = "/tmp/" . md5(microtime()) . ".{$type}";
					if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $img_content)))) {
						$avatar_url = implode('/', Image::upload($new_file));
						$model->photo = $avatar_url;
						$model->save();
						$data = ['status' => 1, 'message' => '上传成功'];
					} else {
						throw new ErrorException('上传失败');
					}
				} else {
					throw new ErrorException('文件格式不正确');
				}
			} catch (ErrorException $e) {
				$data = ['status' => 0, 'message' => $e->getMessage()];
			}
			\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
			return $data;
		}
		return $this->render('avatar', [
			'model' => $model,
		]);
	}

	public function actionWxAvatar()
	{
		try {
			if (\Yii::$app->user->isGuest) {
				throw new ErrorException('用户还没有登录');
			}
			if (!$open_id = \Yii::$app->user->identity->getWxOpenId()) {
				throw new ErrorException('您还没有关注每日惠购公众号');
			}
			if ($user_info = \Yii::$app->wechat->getMemberInfo($open_id)) {
				if (isset($user_info['headimgurl']) && $user_info['headimgurl']) {
					$model = User::findOne(\Yii::$app->user->getId());
					$model->photo = $user_info['headimgurl'];
					$model->save();
					$data = ['status' => 1, 'message' => '上传成功', 'data' => $user_info['headimgurl']];
				} else {
					throw new ErrorException('无法获取您的微信头像或头像不空！');
				}
			} else {
				throw new ErrorException('网络异常，返回重试！');
			}
		} catch (ErrorException $e) {
			$data = ['status' => 0, 'message' => $e->getMessage()];
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}

	public function actionEditMyinfo()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = Customer::findOne(\Yii::$app->user->getId());
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect($url);
		}
		return $this->render('editmyinfo', [
			'model' => $model,
		]);
	}

	public function actionRealName()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = new RealNameForm();
		if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
			return $this->redirect($url);
		}
		return $this->render('realname', ['model' => $model]);
	}

	public function actionSecurityCenter()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = Customer::findOne(\Yii::$app->user->getId());
		return $this->render('securitycenter', [
			'model' => $model,
		]);

	}
	public function actionAjaxSecurityBindUser(){
        $validate_code = \Yii::$app->request->post("validate_code");
        if(!$redirect = \Yii::$app->request->post("redirect_url")){
            $redirect = '/user/index';
        }
        if($validate_code){
            $validate_code = base64_decode($validate_code);
            $v_arr = explode('-',$validate_code);
            list($verify_code_id,$telephone,$code) = $v_arr;
            if($verify_code = VerifyCode::findOne(['verify_code_id'=>$verify_code_id,'phone'=>$telephone,'code'=>$code,'status'=>1])){
                $target_customer = Customer::findOne(['telephone'=>$telephone]);
                //CustomerAuthentication::updateAll(['status'=>0],['customer_id'=>$targert_customer->customer_id,'status'=>1]);
                $customer_auth =  CustomerAuthentication::findOne(['customer_id'=>\Yii::$app->user->getId()]);
                if($customer_auth){
                    $customer_auth->customer_id = $target_customer->customer_id;
                    $customer_auth->save();
                    $user = User::findIdentity($customer_auth->customer_id);
                    \Yii::$app->user->login($user, 3600 * 24 * 7);
                    \Yii::$app->cart->loadFromLogin();
                    $msg = '绑定成功';
                    $stauts = true;

                }else{
                    $msg = '您的账号异常，请联系客服 400-968-9870';
                    $stauts = false;
                }
            }else{
                $msg = '数据错误';
                $stauts = false;

            }
            $result['status'] = $stauts;
            $result['msg'] = $msg;
            $result['redirect'] = $redirect;
            return json_encode($result);
        }
    }
    public function actionAjaxSecuritySetTelephone(){
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = "/user/security-center";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }
        $model = new SecuritySetTelephoneForm();
        if ($model->load(\Yii::$app->request->post())) {
            if($model_code=VerifyCode::findOne(['phone'=>$model->telephone,'code'=>$model->verifyCode,'status'=>0])){
                $model_code->status=1;
                $model_code->update();

                $customer = Customer::find()->where(['and','customer_id <>'.\Yii::$app->user->getId(),'telephone='.$model->telephone])->one();
                if(!empty($customer)){
                    $error_code = 'tel_exist';
                    $message = '电话号码'.$model->telephone.' 已经存在';

                    $result['error_code'] = $error_code;
                    $result['message'] = $message;
                    $result['validate_code'] = base64_encode($model_code->verify_code_id.'-'.$model->telephone.'-'.$model_code->code);
                    $result['redirect_url'] = $url;
                    return json_encode($result);
                }else{
                    $user = User::findIdentity(\Yii::$app->user->getId());
                    $user->telephone = $model->telephone;
                    $user->telephone_validate=1;
                    $user->status=1;
                    $user->approved=1;
                    $user->save();

                    $error_code = 0;
                    $message = '绑定成功';
                    $result['error_code'] = $error_code;
                    $result['message'] = $message;
                    $result['redirect_url'] = $url;
                    return json_encode($result);
                }
            }else{
                $error_code = 'code_error';
                $message = '验证码不正确，请重新输入';

                $result['error_code'] = $error_code;
                $result['message'] = $message;
                $result['redirect_url'] = $url;
                return json_encode($result);
            }

        }
    }
	public function actionSecuritySetTelephone()
	{ //老版本做法 新版见AjaxSecuritySetTelephone
        $this->layout = 'main_other';
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = new SecuritySetTelephoneForm();
		if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
			return $this->redirect($url);
		}
		return $this->render('security_set_telephone', [
			'model' => $model,
		]);
	}

	public function actionSecuritySetEmail()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = new SecuritySetEmailForm();
		if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
			return $this->redirect($url);
		}
		return $this->render('security_set_email', [
			'model' => $model,
		]);
	}

	public function actionSecurityUpdatePassword()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		if (!\Yii::$app->session->get('validate_telephone')) {
			return $this->redirect(['/user/security-validate-telephone', 'redirect' => Url::to(['/user/security-update-password', 'redirect' => $url], true)]);
		}
		$model = new SecuritySetPasswordForm();
		if ($model->load(\Yii::$app->request->post()) && $model->resetPassword()) {
			\Yii::$app->session->remove('validate_telephone');
			return $this->redirect($url);
		}
		return $this->render('security_update_password', [
			'model' => $model
		]);
	}

	public function actionSecurityUpdatePaymentpwd()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		if (!\Yii::$app->session->get('validate_telephone')) {
			return $this->redirect(['/user/security-validate-telephone', 'redirect' => Url::to(['/user/security-update-paymentpwd', 'redirect' => $url], true)]);
		}
		$model = new SecuritySetPaymentPwdForm();
		if ($model->load(\Yii::$app->request->post()) && $model->resetPassword()) {
			\Yii::$app->session->remove('validate_telephone');
			return $this->redirect($url);
		}
		return $this->render('security_update_paymentpwd', [
			'model' => $model
		]);
	}

	public function actionSecurityUmsAuth()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = new AuthenticationForm();
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect($url);
		}
		return $this->render('security_ums_auth', [
			'model' => $model,
		]);
	}

	//修改邮箱，支付密码，手机号码时验证的原电话
	public function actionSecurityValidateTelephone()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		if (!\Yii::$app->user->identity->telephone_validate) {
			return $this->redirect(["/user/security-set-telephone", 'redirect' => $url]);
		}
		$model = new SecurityValidateTelephoneForm();
		if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
			\Yii::$app->session->set("validate_telephone",true);
			return $this->redirect($url);
		}
		return $this->render('security_validate_telephone', [
			'model' => $model,
		]);
	}

	public function actionBalance()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		return $this->render('balance');
	}

	public function actionBalanceList()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/security-center";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$model = new CustomerTransaction();
		$dataProvider = new ActiveDataProvider([
			'query' => $model->find()->where(['customer_id' => \Yii::$app->user->identity->getId()])->orderby('customer_transaction_id desc'),
			'pagination' => [
				'pagesize' => '10',
			]
		]);
		return $this->render('balance-list', ['model' => $model, 'dataProvider' => $dataProvider]);
	}
	public function actionQrcode(){
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/user/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		return $this->render('qrcode');
	}
	public function actionQrtest() {
		$QR='D:\work\shopcloud\h5\web\333.png';
		 QrCode::png('D:\work\shopcloud\h5\web\333.png',$QR,3,10,2);
		 $logo='D:\work\shopcloud\h5\web\assets\images\logo_300x300.png';

		if(file_exists($QR))
		{
			$QR = imagecreatefromstring(file_get_contents($QR));
			$logo = imagecreatefromstring(file_get_contents($logo));
			if (imageistruecolor($logo))
			{
				imagetruecolortopalette($logo, false, 65535);//添加这行代码来解决颜色失真问题
			}
			$QR_width = imagesx($QR);
			$QR_height = imagesy($QR);
			$logo_width = imagesx($logo);
			$logo_height = imagesy($logo);
			$logo_qr_width = $QR_width / 5;
			$scale = $logo_width / $logo_qr_width;
			$logo_qr_height = $logo_height / $scale;
			$from_width = ($QR_width - $logo_qr_width) / 2;
			imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);
		}
		imagepng($QR,'xiangyanglog.png');
		// you could also use the following
		// return return QrCode::png($mailTo);
	}

}
