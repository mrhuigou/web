<?php

namespace h5\controllers;
use api\models\V1\Promotion;
use api\models\V1\PromotionDetail;
use api\models\V1\Subject;
use yii\web\NotFoundHttpException;

class TopicController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $subject=\Yii::$app->request->get('subject');
        if($model=Subject::findOne(['code'=>strtoupper($subject)])){
            $Promotions=Promotion::find()->where(['and',"subject_id='".$model->id."'","type='SINGLE'","date_end>='".date('Y-m-d H:i:s',time())."'",'status=1'])->all();
        }else{
            $Promotions=Promotion::find()->where(['and',"image_url<>''","type='SINGLE'","subject<>'YIYUANGOU'","date_end>='".date('Y-m-d H:i:s',time())."'",'status=1'])->all();
        }
        return $this->render('index',['model'=>$model,'details'=>$Promotions]);
    }
    public function actionDetail()
    {
        if(($code=\Yii::$app->request->get('code')) && $model=Promotion::findOne(['code'=>$code]) ){

            if($status=\Yii::$app->request->get('status')){
                if($model->subject){
                    $details=PromotionDetail::find()->joinWith([
                        'promotion'=>function($query){
                            $query ->andFilterWhere(['jr_promotion.status'=>1]);
                        }
                    ])->where(['and',"jr_promotion_detail.begin_date>'".date('Y-m-d 00:00:00',strtotime("+1 day"))."'","jr_promotion_detail.begin_date<'".date('Y-m-d 23:59:59',strtotime("+1 day"))."'",'jr_promotion_detail.status=1',"jr_promotion.subject='".$model->subject."'"])->orderBy('jr_promotion_detail.priority desc,jr_promotion_detail.promotion_detail_id asc')->all();
                }else{
                    $details=PromotionDetail::find()->where(['and','promotion_id='.$model->promotion_id,"begin_date>'".date('Y-m-d H:i:s',time())."'",'status=1'])->orderBy('priority desc,promotion_detail_id asc')->all();
                }
            }else{
                if($model->subject){
                    $details=PromotionDetail::find()->joinWith([
                        'promotion'=>function($query){
                            $query ->andFilterWhere(['jr_promotion.status'=>1]);
                        }
                    ])->where(['and',"jr_promotion_detail.begin_date<='".date('Y-m-d H:i:s',time())."'","jr_promotion_detail.end_date>='".date('Y-m-d H:i:s',time())."'",'jr_promotion_detail.status=1',"jr_promotion.subject='".$model->subject."'"])->orderBy('jr_promotion_detail.priority desc,jr_promotion_detail.promotion_detail_id asc')->all();
                }else{
                    $details=PromotionDetail::find()->where(['and','promotion_id='.$model->promotion_id,"begin_date<='".date('Y-m-d H:i:s',time())."'","end_date>='".date('Y-m-d H:i:s',time())."'",'status=1'])->orderBy('priority desc,promotion_detail_id asc')->all();
                }
            }
            if($model->subject=='PANIC'){
                return $this->render('seckill',['model'=>$model,'details'=>$details]);
            }else{
                return $this->render('detail',['model'=>$model,'details'=>$details]);
            }
        }else{
            throw new NotFoundHttpException("没有找到页面");
        }
    }
}
