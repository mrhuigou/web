<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 17:42
 */
namespace api\controllers\club\v2\filters;
use api\models\V1\Appuser;
use common\models\User;
use Yii;
use yii\base\Controller;
use yii\helpers\Json;
use common\component\response\Result;
class AccessControl extends \yii\base\Behavior{
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
            Controller::EVENT_AFTER_ACTION => 'afterAction'
        ];
    }
    public function beforeAction($event){
        try {
            if (!Yii::$app->request->isPost) {
                Result::Error("this is only post data!");
            }
            if ($t = Yii::$app->request->post('t') && $k = Yii::$app->request->post('k') && $m = Yii::$app->request->post('m')) {
                if ($model = Appuser::findOne(['appuser_code' => $m])) {
                    if ($k != md5($t.$m.$model->appuser_key)) {
                        Result::Error("Wrong key!");
                    }
                } else {
                    Result::Error("App Key Does not exist!");
                }
            } else {
                Result::Error("Wrong token!");
            }
            if ($token = Yii::$app->request->post('z')) {
                if ($token && strpos($token, '|')) {
                    $data = explode("|", $token);
                    if (count($data) == 3 && md5($data[0] . "customer_id") == $data[2]) {
                        if ($user = User::findIdentity(trim($data[0]))) {
                            Yii::$app->user->login($user);
                        }else{
                            Result::Error("用户不存在");
                        }
                    } else {
                        Result::Error("Wrong token!");
                    }
                } else {
                    Result::Error("Wrong token!");
                }
            }
        } catch(\Exception $exp){
            die($exp->getMessage());
        }
    }
    public function afterAction($action){
        return true;
    }

}