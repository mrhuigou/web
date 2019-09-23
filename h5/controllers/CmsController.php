<?php
namespace h5\controllers;

use api\models\V1\Page;
use yii\web\NotFoundHttpException;

class CmsController extends \yii\web\Controller
{
    public function actionIndex(){
        throw new NotFoundHttpException("没有找到当前内容");
    }
    public function actionInfo(){

        if(!$model= Page::findOne(['page_id'=>\Yii::$app->request->get('page_id')])){
            throw new NotFoundHttpException("没有找到当前内容");
        }
        return $this->render('info',['model'=>$model]);
    }
    public function actionInfoapp(){

        if(!$model= Page::findOne(['page_id'=>\Yii::$app->request->get('page_id')])){
            throw new NotFoundHttpException("没有找到当前内容");
        }
        return $this->renderPartial('infoapp',['model'=>$model]);
    }
}