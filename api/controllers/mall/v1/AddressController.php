<?php

namespace api\controllers\mall\v1;
use api\controllers\mall\v1\filters\AccessControl;
use api\models\V1\Address;
use api\models\V1\Customer;
use api\models\V1\District;
use api\models\form\AddressForm;
use common\component\Helper\Helper;
use common\component\image\Image;
use yii\helpers\Json;
use \yii\rest\Controller;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
class AddressController extends  Controller{
   public function behaviors()
   {
       return ArrayHelper::merge(parent::behaviors(), [
           'exceptionFilter' => [
               'class' => AccessControl::className()
           ],
       ]);
   }

    public function actionIndex()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        $customer_id=\Yii::$app->user->getId();

        if(\Yii::$app->user->getIdentity()->address_id && $data = Address::findOne(\Yii::$app->user->getIdentity()->address_id)){
            return Result::Ok($data);
        }else{
            return Result::Error('未设置默认地址');
        }
    }

    public function actionList()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        if($data['address'] = Address::findAll(['customer_id'=>\Yii::$app->user->getId()])){
            $data['default'] = \Yii::$app->user->getIdentity()->address_id;
            return Result::Ok($data);
        }else{
            return Result::Error('未设置地址');
        }
    }

    public function actionDistrictlist()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        if($data = District::findAll(['district_id'=>[7607,7608,9390,7611]])){
            return Result::Ok($data);
        }else{
            return Result::Error('参数错误');
        }
    }

    public function actionSetdefault()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        $address_id = \Yii::$app->request->post('address_id');

        if($data = Address::findOne(['address_id'=>$address_id,'customer_id'=>\Yii::$app->user->getId()])){
            $customer = Customer::findOne(\Yii::$app->user->getId());
            $customer->address_id = $address_id;
            $customer->save();
            return Result::Ok($address_id);
        }else{
            return Result::Error('参数错误');
        }
    }

    public function actionDelete()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        $address_id = \Yii::$app->request->post('address_id');

        if($data = Address::findOne(['address_id'=>$address_id,'customer_id'=>\Yii::$app->user->getId()])){
            $customer = Customer::findOne(\Yii::$app->user->getId());
            if($customer->address_id == $address_id){
                return Result::Error('不能删除默认地址');
            }else{
                $data->delete();
                return Result::Ok($address_id);
            }
        }else{
            return Result::Error('参数错误');
        }
    }

    public function actionCreate()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        $model = new AddressForm();
        $model->firstname = \Yii::$app->request->post('firstname');
        $model->address_1 = \Yii::$app->request->post('address_1');
        $model->postcode = \Yii::$app->request->post('postcode');
        $model->district_id = \Yii::$app->request->post('district_id');
        $model->telephone = \Yii::$app->request->post('telephone');
        $model->is_default = \Yii::$app->request->post('is_default');

        if ($model->validate()) {
            $model = $model->save();
            return Result::Ok($model);
        }else{
            return Result::Error('参数错误');
        }
    }

    public function actionUpdate()
    {
        $data = [];
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }

        $model = new AddressForm();
        $model->firstname = \Yii::$app->request->post('firstname');
        $model->address_1 = \Yii::$app->request->post('address_1');
        $model->postcode = \Yii::$app->request->post('postcode');
        $model->district_id = \Yii::$app->request->post('district_id');
        $model->telephone = \Yii::$app->request->post('telephone');
        $model->is_default = \Yii::$app->request->post('is_default');
        $model->address_id = \Yii::$app->request->post('address_id');

        if ($model->validate()) {
            $model = $model->update();
            return Result::Ok($model);
        }else{
            return Result::Error('参数错误');
        }
    }

}