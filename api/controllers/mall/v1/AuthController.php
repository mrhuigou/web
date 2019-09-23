<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2016/3/28
 * Time: 15:06
 */
namespace api\controllers\mall\v1;
use api\models\V1\CustomerAuthentication;
use common\component\response\Result;
use common\models\User;
use \yii\rest\Controller;

class AuthController extends Controller
{
    public function actionWx()
    {
        if($user_info=\Yii::$app->request->post()){
            if(!$model=CustomerAuthentication::findOne(['identifier'=>$user_info['unionid'],'provider'=>'WeiXin'])){
                $user = new User();
                $user->nickname=$user_info['nickname']?$user_info['nickname']:'无名小兵';
                $user->firstname = $user_info['nickname'];
                $user->gender = $user_info['sex']==1?'男':'女';
                $user->photo = $user_info['headimgurl'];
                $user->setPassword('weinxin@365jiarun');
                $user->generateAuthKey();
                $user->email_validate = 0;
                $user->telephone_validate = 0;
                $user->customer_group_id = 1;
                $user->approved = 1;
                $user->source_from='WeiXin';
                $user->status = 1;
                $user->date_added=date('Y-m-d H:i:s',time());
                $user->save();
                $model=new CustomerAuthentication();
                $model->provider='WeiXin';
                $model->display_name=$user_info['nickname'];
                $model->identifier=$user_info['unionid'];
                $model->openid=$user_info['openid'];
                $model->photo_url=$user_info['headimgurl'];
                $model->gender=$user_info['sex']==1?'男':'女';
                $model->customer_id=$user->customer_id;
                $model->date_added=date('Y-m-d H:i:s',time());
                $model->save();
            }
            return Result::OK(['customer_id'=>$model->customer_id]);
        }else{
            return Result::NO('数据异常');
        }
    }
}