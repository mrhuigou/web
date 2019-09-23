<?php

namespace backend\controllers;

use api\models\V1\Coupon;
use Yii;
use api\models\V1\ExerciseRuleCoupon;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExerciseRuleCouponController implements the CRUD actions for ExerciseRuleCoupon model.
 */
class ExerciseRuleCouponController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ExerciseRuleCoupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => ExerciseRuleCoupon::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExerciseRuleCoupon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ExerciseRuleCoupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExerciseRuleCoupon();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'exercise_rule_id' => $model->exercise_rule_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExerciseRuleCoupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
	        return $this->redirect(['index', 'exercise_rule_id' => $model->exercise_rule_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExerciseRuleCoupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
	    $model = $this->findModel($id);
	    $exercise_rule_id=$model->exercise_rule_id;
	    $model->delete();
        return $this->redirect(['index','exercise_rule_id' => $exercise_rule_id]);
    }
	public function actionAutoComplete(){
		$data=[];
		if($query=Yii::$app->request->get('term')){
			if($coupon_datas=Coupon::find()->where("code like '%".trim($query)."%'")->orderBy('coupon_id asc')->limit(10)->all()){
				foreach($coupon_datas as $value){
					$data[]=[
						'value'=>$value->coupon_id,
						'label'=>"[".$value->coupon_id."]  ".$value->code."  [".$value->name."]",
					];
				}
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
    /**
     * Finds the ExerciseRuleCoupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExerciseRuleCoupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExerciseRuleCoupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
