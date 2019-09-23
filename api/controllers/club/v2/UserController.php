<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/8/20
 * Time: 11:00
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\Customer;
use \yii\rest\Controller;
use common\component\image\Image;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
class UserController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionBase(){
        if(!\Yii::$app->request->post('customer_id')){
            Result::Error('缺少参数customer_id');
        }
        if($model=Customer::findOne(['customer_id'=>\Yii::$app->request->post('customer_id')])){
            $data=[
                'customer_id'=>$model->customer_id,
                'nickname'=>$model->nickname,
                'photo'=>$model->photo?Image::resize($model->photo,100,100):'',
                'gender'=>$model->gender?$model->gender:'保密',
                'birthday'=>$model->birthday?$model->birthday:'',
            ];
            return Result::OK($data);
        }else{
            Result::Error('用户信息不存在');
        }

    }
}