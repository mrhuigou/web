<?php
namespace fx\controllers;

use api\models\V1\AffiliatePlan;
use api\models\V1\Page;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class AffiliatePlanDetailController extends Controller
{
    public function actionIndex(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
        }

        if(!$plan_id = Yii::$app->request->get("plan_id")){
            $position = Yii::$app->request->get("position");
            if($affiliatePlan=AffiliatePlan::find()->where(['and',"position='".$position."'",'date_start<=NOW()','date_end>=NOW()','status=1'])->one()){
                $plan_id = $affiliatePlan->affiliate_plan_id;
            }
        }
        $plan = AffiliatePlan::findOne($plan_id);

        if(!empty($plan)){
            return $this->render("index",['plan'=>$plan]);
        }else{
            throw new NotFoundHttpException("没有找到该页面！");
        }
    }
}