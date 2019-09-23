<?php
namespace frontend\controllers;

use api\models\V1\Promotion;
use yii\web\Controller;

class PromotionController extends Controller
{
    public function actionIndex(){
        $subject=\Yii::$app->request->get('subject');
        if($model=Promotion::find()->where(['and',"subject='".strtoupper($subject)."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one()){
            return $this->redirect(['/topic/detail','code'=>$model->code]);
        }else{
            return $this->redirect(['/topic/index','subject'=>$subject]);
        }
    }

}
