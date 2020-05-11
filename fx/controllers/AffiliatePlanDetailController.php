<?php
namespace fx\controllers;

use api\models\V1\Page;
use fx\widgets\Affiliate\AffiliatePlan;
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

        $plan_id = Yii::$app->request->get("plan_id");
        $plan = \api\models\V1\AffiliatePlan::findOne($plan_id);

        if(!empty($plan)){
            return $this->render("index",['plan'=>$plan]);
        }else{
            throw new NotFoundHttpException("没有找到该页面！");
        }
    }
}