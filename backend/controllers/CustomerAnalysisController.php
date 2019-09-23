<?php

namespace backend\controllers;

use api\models\V1\Customer;
use backend\models\searchs\CustomerAnalysisSearch;
use api\models\V1\CustomerSource;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * GroundPushPointController implements the CRUD actions for GroundPushPoint model.
 */
class CustomerAnalysisController extends Controller
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
        $searchModel = new  CustomerAnalysisSearch();
        $datas = $searchModel->search(Yii::$app->request->queryParams);
        //$datas = $dataProvider->getModels();

        $data_dm = $searchModel->getDM(Yii::$app->request->queryParams);
       // $data_dm = $dataProvider->getModels();
        $data_gd = $searchModel->getGD(Yii::$app->request->queryParams);
        //$data_gm = $dataProvider->getModels();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'datas_all'=>$datas,
            'datas_dm' => $data_dm,
            'datas_gd' => $data_gd,
        ]);

    }
    public function getCustomerDatas($type,$begin_date,$end_date){
        $subQuery = Customer::find()->where(['and','date_added >="'.$begin_date.'"' ,'date_added <="'.$end_date.'"']);
        $Query = new \yii\db\Query();
        $data_format = '%Y-%m-%d';
        $Query->select(["DATE_FORMAT(tmp.date_added, '".$data_format."') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
        $Query->groupBy("date")->limit(10);
        $datas = $Query->createCommand()->queryAll();
        return $datas;

    }
    public function actionCustomerOrders(){

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


}
