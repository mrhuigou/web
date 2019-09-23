<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/3/10
 * Time: 11:05
 */
namespace api\controllers\mall\v1;

use api\models\form\ForgetForm;
use api\models\form\LoginForm;
use api\models\form\ResetPwdForm;
use api\models\form\SignupForm;
use api\models\V1\AdvertiseDetail;
use api\models\V1\CategoryDisplay;
use api\models\V1\VerifyCode;
use common\component\Helper\Helper;
use common\component\image\Image;
use common\component\Message\Sms;
use common\component\response\Result;
use common\component\Sms\VoiceVerify;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use \yii\rest\Controller;

class MainController extends Controller
{
    public function actionIndex()
    {
        $data = array();
        /*获取滚动banner*/
        $data['silde'] = $this->getAdvDatas(['H5-0F-SLIDE']);
        $data['hot'] = $this->getAdvDatas(['H5-0F-AD']);

        $temp = $this->getAdvDatas(['H5-1F-SECKILL']);
        $temp = ArrayHelper::merge($temp, $this->getAdvDatas(['H5-1F-FREEDLY']));
        $temp = ArrayHelper::merge($temp, $this->getAdvDatas(['H5-1F-ACTION']));
        $data['topic_city'] = ArrayHelper::merge($temp, $this->getAdvDatas(['H5-1F-PROMOTION']));
        $temp = $this->getAdvDatas(['H5-2F-FRESHDRINK']);
        $data['topic_life'] = ArrayHelper::merge($temp, $this->getAdvDatas(['H5-2F-LIVE']));
        $data['ad_1'] = $this->getAdvDatas(['secondF_LIVE']);
        $data['brand'] = $this->getAdvDatas(['H5-3F-BRAND']);

        $data['slide_tab'][] = [
            'product' => $this->getAdvDatas(['H5-4F-PRODUCT-1']),
            'brand' => $this->getAdvDatas(['H5-4F-PBRAND-1']),
            'logo' => $this->getAdvDatas(['H5-4F-PLOGO-1'])
        ];
        $data['slide_tab'][] = [
            'product' => $this->getAdvDatas(['H5-4F-PRODUCT-2']),
            'brand' => $this->getAdvDatas(['H5-4F-PBRAND-2']),
            'logo' => $this->getAdvDatas(['H5-4F-PLOGO-2'])
        ];
        $data['slide_tab'][] = [
            'product' => $this->getAdvDatas(['H5-4F-PRODUCT-3']),
            'brand' => $this->getAdvDatas(['H5-4F-PBRAND-3']),
            'logo' => $this->getAdvDatas(['H5-4F-PLOGO-3'])
        ];
        $data['slide_tab'][] = [
            'product' => $this->getAdvDatas(['H5-4F-PRODUCT-4']),
            'brand' => $this->getAdvDatas(['H5-4F-PBRAND-4']),
            'logo' => $this->getAdvDatas(['H5-4F-PLOGO-4'])
        ];
        $data['slide_tab'][] = [
            'product' => $this->getAdvDatas(['H5-4F-PRODUCT-5']),
            'brand' => $this->getAdvDatas(['H5-4F-PBRAND-5']),
            'logo' => $this->getAdvDatas(['H5-4F-PLOGO-5'])
        ];

        return  Result::OK($data);
    }

    public function actionCategory()
    {
        $data = [];
        $model = CategoryDisplay::find()->where(['status' => '1'])->andWhere('category_display_id>501')->all();
        if ($model) {
            foreach ($model as $value) {
                $data[] = [
                    'id' => $value->category_display_id,
                    'pid' => $value->parent_id,
                    'name' => $value->description ? $value->description->name : ''
                ];
            }
        }
        return  Result::OK(Helper::genTree($data));
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(['SignupForm'=>\Yii::$app->request->post()]) && $user = $model->save()) {
            return Result::OK(['customer_id'=>$user->customer_id,'nickname'=>$user->nickname,'photo'=>Image::resize($user->photo,100,100),'gender'=>$user->gender,'signature'=>$user->signature]);
        } else {
            if ($model->hasErrors()) {
                return Result::NO(current($model->getFirstErrors()));
            } else {
                return Result::NO('数据异常');
            }
        }
    }
    public function actionLogin()
    {
        $model = new LoginForm();
        if ($model->load(['LoginForm'=>\Yii::$app->request->post()]) && $user = $model->login()) {
            return Result::OK(['customer_id'=>$user->customer_id,'nickname'=>$user->nickname,'photo'=>Image::resize($user->photo,100,100),'gender'=>$user->gender,'signature'=>$user->signature]);
        } else {
            if ($model->hasErrors()) {
                return Result::NO(current($model->getFirstErrors()));
            } else {
                return Result::NO('数据异常');
            }
        }
    }
    public function actionForget(){
        $model = new ForgetForm();
        if ($model->load(['ForgetForm'=>\Yii::$app->request->post()]) && $user = $model->save()) {
            return Result::OK(['password_reset_token'=>$user->password_reset_token]);
        } else {
            if ($model->hasErrors()) {
                return Result::NO(current($model->getFirstErrors()));
            } else {
                return Result::NO('数据异常');
            }
        }
    }
    public function actionResetPwd($token){
        try {
            $model = new ResetPwdForm($token);
        } catch (InvalidParamException $e) {
            return Result::NO($e->getMessage());
        }
        if ($model->load(['ResetPwdForm'=>\Yii::$app->request->post()]) && $user=$model->resetPassword()) {
            return Result::OK(['customer_id'=>$user->customer_id,'nickname'=>$user->nickname,'photo'=>Image::resize($user->photo,100,100),'gender'=>$user->gender,'signature'=>$user->signature]);
        }else{
            if ($model->hasErrors()) {
                return Result::NO(current($model->getFirstErrors()));
            } else {
                return Result::NO('数据异常');
            }
        }
    }
    public function actionSendCode(){
        $status=false;
        if($username=trim(\Yii::$app->request->post('username'))){
            if(strpos($username,'@')){
                if(!$model=VerifyCode::findOne(['phone'=>$username,'status'=>0])){
                    $code=rand(100000,999999);
                    $model=new VerifyCode();
                    $model->phone=strval($username);
                    $model->code=strval($code);
                    $model->status=0;
                    $model->date_added=date('Y-m-d H:i:s',time());
                    $model->save();
                }
                $message="您的家润验证码:".$model->code."，请勿将验证码泄露给其他人。";
                \Yii::$app->mailer->compose()
                    ->setTo($username)
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setSubject('家润慧生活邮箱验证码')
                    ->setTextBody('尊敬的会员：'.$message)
                    ->send();
                $status=true;
            }else{
                if($model=VerifyCode::findOne(['phone'=>trim($username),'status'=>0])){
                    $model->status=1;
                    $model->save();
                }
                $code=rand(1000,9999);
                $model=new VerifyCode();
                $model->phone=trim($username);
                $model->code=strval($code);
                $model->status=0;
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->save();
                $voice=new VoiceVerify();
                if($voice->send($this->telephone,$model->code)){
                    $status=true;
                }
            }
        }
        if($status){
            return Result::OK('发送成功');
        }else{
            return Result::NO('发送失败');
        }

    }
    private function getAdvDatas($code_array = [])
    {
        $datas = [];
        if ($code_array) {
            $advertise = new AdvertiseDetail();
            $position_ads = $advertise->getAdvertiserDetailByPositionCode($code_array);
            if (!empty($position_ads)) {
                $position_data = $advertise->getAdvertiseInfo($position_ads);
                $datas = $position_data;
            }
        }
        return $datas;
    }
}