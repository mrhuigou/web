<?php

namespace backend\controllers;

use api\models\V1\CouponRules;
use api\models\V1\CouponRulesDetail;
use backend\models\searchs\CouponRulesSearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * LotteryController implements the CRUD actions for Lottery model.
 */
class CouponRulesController extends Controller
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
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'common\extensions\widgets\ueditor\UEditorAction',
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@backend/web/upload/images'
            ],
        ];
    }

    /**
     * Lists all Lottery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponRulesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Lottery model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $details = $model->details;
//print_r($lottery_prizes);exit;
        return $this->render('view', [
            'model' => $model,
            'details'=>$details
        ]);
    }

    /**
     * Creates a new Lottery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CouponRules();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coupon_rules_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Lottery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coupon_rules_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Lottery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lottery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Lottery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouponRules::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDetailUpdate($id){
        $model = CouponRulesDetail::findOne(['coupon_rules_detail_id'=>$id]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['coupon-rules/view', 'id' => $model->coupon_rules_id]);
        } else {
            return $this->render('detail_update', [
                'model' => $model,
            ]);
        }
    }
    public function actionDetailCreate()
    {
        $coupon_rules_id = Yii::$app->request->get("coupon_rules_id");

        $model = new CouponRulesDetail();
        $model->coupon_rules_id = $coupon_rules_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['coupon-rules/view', 'id' => $model->coupon_rules_id]);
        } else {
            return $this->render('detail_create', [
                'model' => $model,
            ]);
        }
    }
}
