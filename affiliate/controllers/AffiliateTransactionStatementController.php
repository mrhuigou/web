<?php

namespace affiliate\controllers;

use api\models\V1\AffiliateTransactionStatementSearch;
use common\extensions\widgets\xlsxwriter\xlsxwriter as XLSXWriter;
use Yii;
use api\models\V1\AffiliateTransaction;
use api\models\V1\AffiliateTransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AffiliateTransactionStatementController implements the CRUD actions for AffiliateTransaction model.
 */
class AffiliateTransactionStatementController extends Controller
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
     * Lists all AffiliateTransaction models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $searchModel = new AffiliateTransactionStatementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AffiliateTransaction model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the AffiliateTransaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AffiliateTransaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AffiliateTransaction::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionExport(){
        $searchModel = new AffiliateTransactionStatementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setPagination(['pagesize'=>$dataProvider->totalCount]);
        $header = array(
            'Title'=>'string',
            'Amount'=>'string',
            'Balance'=>'string',
            'Remark'=>'string',
            '创建时间'=>'string',
        );
        $writer = new XLSXWriter();
        $writer->writeSheetHeader('Sheet1', $header );
        if($model=$dataProvider->getModels()){
            foreach($model as $value){
                $writer->writeSheetRow('Sheet1',[
                    $value['title'],
                    $value['amount'],
                    $value['balance'],
                    $value['remark'],
                    date('Y-m-d H:i:s',$value['create_at'])
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
