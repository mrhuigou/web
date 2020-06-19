<?php

namespace affiliate\controllers;

use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use affiliate\models\OrderSearch;
use yii\web\Controller;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport(){
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
        $header = array(
            '订单编号'=>'string',
            '订单总额'=>'string',
            '订单状态'=>'string',
            '订单佣金'=>'string',
            '创建时间'=>'string',
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        if($model=$dataProvider->getModels()){
            foreach($model as $value){
                $writer->writeSheetRow('Sheet1',[
                    $value['order_no'],$value['total'],
                    $value['order_status_id'],
                    $value['commission'],
                    $value['date_added']
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
