<?php

namespace backend\controllers;

use api\models\V1\ReportData;
use api\models\V1\ReportSaleCouponSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ReportSaleDataController extends Controller
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
	    $query=ReportData::find();
	    $dataProvider = new ActiveDataProvider([
		    'query' => $query,
	    ]);
        return $this->render('index',['dataProvider'=>$dataProvider]);
    }
	public function actionCreate(){
		$title=Yii::$app->request->post('title');
		$content=Yii::$app->request->post('content');
		if($title && $content){
			$model=new ReportData();
			$model->title=$title;
			$model->content=$content;
			$model->date_added=date('Y-m-d H:i:s',time());
			$model->save();
		}
	}
	public function actionUpdate(){

	}
	public function actionDel(){

	}
	public function actionExecute($id){
		if($model=ReportData::findOne($id)){
			$dataProvider = new SqlDataProvider([
				'sql' => $model->content,
			]);
			$attr=[];
			if($dataProvider->models[0]){
				foreach ($dataProvider->models[0] as $key=>$value){
					$attr[]=$key;
				}
			}
			return $this->render('execute',['dataProvider'=>$dataProvider,'attr'=>$attr]);
		}else{
			throw  new NotFoundHttpException("没有找到相关数据");
		}
	}
}