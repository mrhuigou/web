<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/7/1
 * Time: 16:58
 */
namespace api\controllers\club\v2;
use api\controllers\club\v2\filters\AccessControl;
use api\models\V1\ClubUserContact;
use yii\helpers\Json;
use \yii\rest\Controller;
use common\component\response\Result;
use yii\helpers\ArrayHelper;
class ContactController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'exceptionFilter' => [
                'class' => AccessControl::className()
            ],
        ]);
    }
    public function actionList(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $model=ClubUserContact::find()->where(['customer_id'=>\Yii::$app->user->getId()]);
        $data['totalCount']=$model->count();
        $data['list']=[];
        $model=$model->all();
        if($model){
            foreach($model as $value){
                $data['list'][]=[
                    'id'=>$value->id,
                    'username'=>$value->username,
                    'telephone'=>$value->telephone,
                    'is_member'=>$value->memberStatus?1:0,
                    'status'=>$value->status
                ];
            }
        }
        return Result::OK($data);
    }
    public function actionSubmit(){
        if(\Yii::$app->user->isGuest){
            Result::Error('缺少用户身份');
        }
        $datas= Json::decode(\Yii::$app->request->post('data'));
        $customer_id=\Yii::$app->user->getId();
        if($datas){
            foreach($datas as $data){
                if(isset($data['username']) && isset($data['telephone']) ){
                    if(!$model=ClubUserContact::findOne(['customer_id'=>$customer_id,'telephone'=>$data['telephone']])){
                        $model=new ClubUserContact();
                        $model->status=0;
                        $model->creat_at=date('Y-m-d H:i:s',time());
                    }
                    $model->customer_id=$customer_id;
                    $model->username=$data['username'];
                    $model->telephone=$data['telephone'];
                    $model->update_at=date('Y-m-d H:i:s',time());
                    if(!$model->save()){
                        Result::Error('数据保存异常');
                    }
                }else{
                    Result::Error('数据格式不正确');
                }
            }
        }
        return Result::OK();
    }

}