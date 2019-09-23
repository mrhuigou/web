<?php
namespace frontend\controllers;
use api\models\V1\Page;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use api\models\V1\Subject;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class TopicController extends Controller
{
    public function actionIndex()
    {
        $subject=\Yii::$app->request->get('subject');
        if($model=Subject::findOne(['code'=>strtoupper($subject)])){
            $Promotions=Promotion::find()->where(['and',"subject_id='".$model->id."'","date_end>='".date('Y-m-d H:i:s',time())."'",'status=1'])->all();
            if($Promotions){
                return $this->render('index',['model'=>$model,'details'=>$Promotions]);
            }else{
                throw new NotFoundHttpException("没有任何活动信息");
            }
        }else{
            throw new NotFoundHttpException("没有找到页面");
        }
    }
    public function actionDetail()
    {
        if(($code=\Yii::$app->request->get('code')) && $model=Promotion::findOne(['code'=>$code]) ){
            $details=PromotionDetail::find()->where(['and','promotion_id='.$model->promotion_id,"begin_date<='".date('Y-m-d H:i:s',time())."'","end_date>='".date('Y-m-d H:i:s',time())."'",'status=1'])->orderBy('priority desc,promotion_detail_id asc')->all();
            return $this->render('detail',['model'=>$model,'details'=>$details]);
        }else{
            throw new NotFoundHttpException("没有找到页面");
        }
    }
}