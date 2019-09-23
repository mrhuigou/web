<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/8
 * Time: 20:23
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\ClubUserInvite;
use api\models\V1\ClubUserInviteLog;
use common\component\image\Image;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
use \yii\rest\Controller;

class InviteController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionCode(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if(!$type=\Yii::$app->request->post('type')){
            Result::Error('缺少Type');
        }
        if(!$type_id=\Yii::$app->request->post('type_id')){
            Result::Error('缺少Type_id');
        }
        if(!$model=ClubUserInvite::findOne(['customer_id'=>\Yii::$app->user->getId(),'type'=>$type,'type_id'=>$type_id])){
            $model=new ClubUserInvite();
            $model->customer_id=\Yii::$app->user->getId();
            $model->type=$type;
            $model->type_id=$type_id;
            $model->code=strval(time().rand(10000,999999));
            $model->creat_at=date('Y-m-d H:i:s',time());
            $model->save();
        }
        return Result::OK(['invite_code'=>$model->code]);
    }
    public function actionUser(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        if(!$type=\Yii::$app->request->post('type')){
            Result::Error('缺少Type');
        }
        if(!$type_id=\Yii::$app->request->post('type_id')){
            Result::Error('缺少Type_id');
        }
        $data['totalCount']=0;
        $data['list']=[];
        $mode=ClubUserInvite::findOne(['customer_id'=>\Yii::$app->user->getId(),'type'=>$type,'type_id'=>$type_id]);
        if($mode){
            $log=ClubUserInviteLog::find()->where(['invite_id'=>$mode->id]);
            $data['totalCount']=$log->count();
            $log=$log->all();
            if($log){
                foreach($log as $value){
                    $data['list'][]=[
                        'customer_id'=>$value->customer_id,
                        'nickname'=>$value->customer->nickname,
                        'photo'=>Image::resize($value->customer->photo,100,100),
                    ];
                }
            }
        }
        return Result::OK($data);
    }
}