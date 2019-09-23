<?php

namespace backend\controllers;
use api\models\V1\ReportZoneSearch;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
/**
 * RefundController implements the CRUD actions for ReturnBase model.
 */
class ReportZoneController extends Controller
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
    public function actionIndex(){

        $searchModel = new ReportZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
    public function actionExport(){
        $searchModel = new ReportZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
        $header = array(
            '订单总额'=>'string',
            '用户ID'=>'string',
            '电话'=>'string',
            '收货地址'=>'string',
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        if($model=$dataProvider->getModels()){
            foreach($model as $value){
                $writer->writeSheetRow('Sheet1',[
                    $value->total,$value->customer_id,$value->telephone,$value->orderShipping?$value->orderShipping->shipping_address_1:''
                ]);
            }
        }

        $writer->writeToFile('/tmp/output.xlsx');

        // 输入文件标签
        header("Content-Type:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8");
        header("Content-Disposition: attachment; filename=output.xlsx");  //File name extension was wrong
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        ob_clean();

        // 输出文件内容
        readfile('/tmp/output.xlsx');

    }
}