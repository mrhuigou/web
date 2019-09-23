<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/8/9
 * Time: 16:17
 */
namespace h5\controllers;

use api\models\V1\GroundPushPlan;
use api\models\V1\GroundPushPlanView;
use api\models\V1\GroundPushPoint;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class AppScanController extends \yii\web\Controller {
	public function actionIndex(){
        $point_id = \Yii::$app->request->get("id");
        $token = \Yii::$app->request->get('token');
        $t = \Yii::$app->request->get('t');
        $exp_time = time() - $t;//token存在时间
        if($exp_time > 3600){
            return $this->redirect(['/ground-push/list']);
        }
        try{
            if($point_id ){ //token一小时有效期

                $ground_push_point = GroundPushPoint::findOne(['id'=>$point_id,'status'=>1]);
                if($ground_push_point){
                    if(md5($ground_push_point->pass.'-'.$t) == $token ){
                        //token正确
                        if($exp_time > 1800){
                            //快要过期 刷新token
                            $new_time = time();
                            $new_token = md5($ground_push_point->pass.'-'.$new_time);
                            return $this->redirect(['/app-scan/index','id'=>$point_id,'token'=>$new_token,'t'=>$new_time]);
                        }
                    }else{
                        //token不正确，需重新登录
                        return $this->redirect(['/ground-push/list']);
                    }
                    $plan = GroundPushPlan::find()->where(['ground_push_point_id'=>$ground_push_point->id,'status'=>1])->andWhere(['and','begin_date_time < NOW()'])->one();
//                    if(strtotime($plan->end_date_time) <= time()){
//                        throw new  Exception("地推方案不存在或已过期");
//                    }
                    $products = GroundPushPlanView::find()->where(['status' => 1, 'ground_push_plan_id' => $plan->id])->orderBy('sort_order asc')->all();
                    return $this->render('index',['ground_push_point'=>$ground_push_point,'plan'=>$plan,'products'=>$products]);
                }else{
                    throw new  Exception("地推点不存在");
                }

            }else{
                throw new  Exception("错误的地址码");
            }
        }catch (Exception $e){
            throw new  NotFoundHttpException($e->getMessage());
        }

	}
	public function actionAjaxInput(){
	    $code = \Yii::$app->request->post("code");

    }
}