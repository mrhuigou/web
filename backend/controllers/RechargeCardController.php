<?php

namespace backend\controllers;

use Yii;
use api\models\V1\RechargeCard;
use api\models\V1\RechargeCardSearch;
use backend\models\RechargecardForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;

/**
 * RechargeCardController implements the CRUD actions for RechargeCard model.
 */
class RechargeCardController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all RechargeCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RechargeCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        $searchModel = new RechargeCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,'export');
        $header = array(
          '序号'=>'string',
          '名称'=>'string',
          '面值'=>'string',
          '编码'=>'string',
            '卡号'=>'string',
          '密码'=>'string',
          '开始时间'=>'string',
          '失效时间'=>'string',
          '生成时间'=>'string',
          '激活时间'=> 'string',
          '状态'=>'string',
        );
        $status = ['未激活','已激活'];
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        foreach ($dataProvider->models as $key => $value) {
            # code...
            $activation_time = '0000-00-00 00:00:00';
            $history =  $value->rechargeHistory;
            if($history){
                $activation_time = $history->created_at;
            }
            $writer->writeSheetRow('Sheet1', array($key+1,$value->title,$value->value,$value->card_code,$value->card_no,$value->card_pin,$value->start_time,$value->end_time,$value->created_at,$activation_time,$status[$value->status]));
        }
        $pre = date("YmdHis").rand(1000,9999);
        $writer->writeToFile('/tmp/'.$pre.'.xlsx');

        // 输入文件标签
        header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header("Content-Disposition: attachment; filename=".date("YmdHis").".xlsx");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        ob_clean();

        // 输出文件内容
        readfile('/tmp/'.$pre.'.xlsx');
    }

    /**
     * Displays a single RechargeCard model.
     * @param string $id
     * @return mixed
     */
     public function actionView($id)
     {
         return $this->render('view', [
             'model' => $this->findModel($id),
         ]);
     }

    /**
     * Creates a new RechargeCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RechargecardForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing RechargeCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    // public function actionUpdate($id)
    // {
    //     $model = $this->findModel($id);

    //     if ($model->load(Yii::$app->request->post()) && $model->save()) {
    //         return $this->redirect(['view', 'id' => $model->id]);
    //     } else {
    //         return $this->render('update', [
    //             'model' => $model,
    //         ]);
    //     }
    // }

    /**
     * Deletes an existing RechargeCard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    /**
     * Finds the RechargeCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RechargeCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($model = RechargeCard::findOne($id)) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
