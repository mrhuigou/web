<?php

namespace frontend\controllers;

use common\models\SecuritySetEmailForm;
use common\models\SecuritySetPasswordForm;
use common\models\SecuritySetPaymentPwdForm;
use common\models\SecuritySetTelephoneForm;
use common\models\SecurityValidateTelephoneForm;
use frontend\models\ResetPasswordForm;
use frontend\models\AuthenticationForm;
use api\models\V1\Customer;
use api\models\V1\CustomerUmsauth;
use common\models\User;
use api\models\V1\VerifyCode;
use common\component\Helper\Helper;
use common\component\Helper\Mcrypt;
use yii\helpers\Url;

class SecurityController extends \yii\web\Controller
{
    public $layout = 'main-user';
    
    public function actionIndex()
    {
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
    	$model = Customer::findOne(\Yii::$app->user->getId());
        return $this->render('index',['model'=>$model]);
    }

    public function actionUpdatePassword()
    {
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(!\Yii::$app->session->get('SecurityValidateTelephone')){
            return $this->redirect(['/security/security-validate-telephone','redirect'=>Url::to(['/security/update-password','redirect'=>$url],true)]);
        }
    	$model = new SecuritySetPasswordForm();
        if ($model->load(\Yii::$app->request->post()) && $model->resetPassword()) {
            \Yii::$app->getSession()->setFlash('success', '密码修改成功！');
            \Yii::$app->session->remove('SecurityValidateTelephone');
            return $this->redirect($url);
        }
        return $this->render('update_password', [
            'model' => $model
        ]);
    }
    public function actionAuthentication()
    {
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }

        $model = new AuthenticationForm();
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect($url);
        }
        return $this->render('authentication', [
                'model' => $model,
        ]);
    }
    public function actionSecuritySetTelephone(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(\Yii::$app->user->identity->telephone_validate == 1){
            if(!\Yii::$app->session->get('SecurityValidateTelephone')){
                return $this->redirect(['/security/security-validate-telephone','redirect'=>Url::to(['/security/security-set-telephone','redirect'=>$url],true)]);
            }
        }
        $model = new SecuritySetTelephoneForm();
        if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
            \Yii::$app->session->set('SecurityValidateTelephone',true);
            return $this->redirect($url);
        }
        return $this->render('security_set_telephone', [
            'model' => $model,
        ]);
    }
    //修改邮箱，支付密码，手机号码时验证的原电话
    public function actionSecurityValidateTelephone(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(\Yii::$app->user->identity->telephone){
            $model = new SecurityValidateTelephoneForm();
            if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
                \Yii::$app->session->set('SecurityValidateTelephone',true);
                return $this->redirect($url);
            }
            return $this->render('security_validate_telephone', [
                'model' => $model,
            ]);
        }else{
            return $this->redirect(["/security/security-set-telephone",'redirect'=>$url]);
        }
    }
    public function actionSecuritySetEmail(){
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(!\Yii::$app->session->get('SecurityValidateTelephone')){
            return $this->redirect(['/security/security-validate-telephone','redirect'=>Url::to(['/security/security-set-email','redirect'=>$url],true)]);
        }
        $model = new SecuritySetEmailForm();
        if ($model->load(\Yii::$app->request->post()) && $model->bindName()) {
            \Yii::$app->session->remove('SecurityValidateTelephone');
            return $this->redirect($url);
        }
        return $this->render('security_set_email', [
            'model' => $model,
        ]);
    }
    public function actionSecurityUpdatePaymentpwd()
    {
        if(!$url=\Yii::$app->request->get('redirect')){
            $url = "/security/index";
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>$url]);
        }
        if(!\Yii::$app->session->get('SecurityValidateTelephone')){
            return $this->redirect(['/security/security-validate-telephone','redirect'=>Url::to(['/security/security-update-paymentpwd','redirect'=>$url],true)]);
        }
        $model = new SecuritySetPaymentPwdForm();
        if ($model->load(\Yii::$app->request->post()) && $model->resetPassword()) {
            \Yii::$app->session->remove('SecurityValidateTelephone');
            return $this->redirect($url);
        }
        return $this->render('security_update_paymentpwd', [
            'model' => $model
        ]);
    }


}
