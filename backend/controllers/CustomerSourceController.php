<?php

namespace backend\controllers;

use api\models\V1\CustomerSource;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroundPushPointController implements the CRUD actions for GroundPushPoint model.
 */
class CustomerSourceController extends Controller
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
    public function actionBar(){
        $Query = new \yii\db\Query();
        $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m-%d') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total"])->from(['tmp'=>$subQuery]);
        $Query->groupBy("date");
        $Query->createCommand()->execute();
    }

    /**
     * Lists all GroundPushPoint models.
     * @return mixed
     */
    public function actionIndex()
    {
        $type = Yii::$app->request->get('type','days');//'days'; weeks ; months

        switch ($type)
        {
            case 'days':
                $date = date("Y-m-d");
                $begin_date = $date.' 00:00:00';
                $end_date = $date.' 23:59:59';
                $data = $this->getCustomerSources($type,$begin_date,$end_date);
                break;
            case 'weeks':
                $begin_date = date('Y-m-d w', strtotime('first day this week'));
                $end_date = date('Y-m-d', strtotime('last day this week'));

                $data = $this->getCustomerSources($type,$begin_date,$end_date);
                break;
            default:
                $date = date("Y-m-d");
                $begin_date = $date.' 00:00:00';
                $end_date = $date.' 23:59:59';
                $data = $this->getCustomerSources($type,$begin_date,$end_date);
                break;
        }


        return $this->render('index', $data);
    }
    private function getCustomerSources($type,$begin_date,$end_date){
        $source_from_type1 = 2 ;//2：地推   3：传单派发
        $source_from_type2 = 3;

        $total_customer_count = CustomerSource::find()->where(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $total_new_customer_count = CustomerSource::find()->where(['is_new_customer'=>1])->andWhere(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $total_old_customer_count = $total_customer_count -  $total_new_customer_count;

        $GD_total_customer_count = CustomerSource::find()->where(['source_from_type'=>$source_from_type1])->andWhere(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $GD_new_customer_count = CustomerSource::find()->where(['source_from_type'=>$source_from_type1,'is_new_customer'=>1])->andWhere(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $GD_old_customer_count = $GD_total_customer_count - $GD_new_customer_count;

        $DM_total_customer_count = CustomerSource::find()->where(['source_from_type'=>$source_from_type2])->andWhere(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $DM_new_customer_count = CustomerSource::find()->where(['source_from_type'=>$source_from_type2,'is_new_customer'=>1])->andWhere(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"'])->groupBy('customer_id')->count();
        $DM_old_customer_count = $DM_total_customer_count - $DM_new_customer_count;

        $data['total_customer_count'] = $total_customer_count;
        $data['total_new_customer_count'] = $total_new_customer_count;
        $data['total_old_customer_count'] = $total_old_customer_count;
        $data['GD_total_customer_count'] = $GD_total_customer_count;
        $data['GD_new_customer_count'] = $GD_new_customer_count;
        $data['GD_old_customer_count'] = $GD_old_customer_count;
        $data['DM_total_customer_count'] = $DM_total_customer_count;
        $data['DM_new_customer_count'] = $DM_new_customer_count;
        $data['DM_old_customer_count'] = $DM_old_customer_count;
        return $data;
    }

    /**
     * Displays a single GroundPushPoint model.
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
     * Creates a new GroundPushPoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GroundPushPoint();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing GroundPushPoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing GroundPushPoint model.
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
     * Finds the GroundPushPoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GroundPushPoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GroundPushPoint::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
