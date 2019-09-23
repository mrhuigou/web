<?php
namespace frontend\controllers;

use api\models\V1\Page;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * Site controller
 */
class PageController extends Controller
{
    public function actionIndex(){
        $page_id = Yii::$app->request->get("page_id");
        $page = Page::find()->where(['page_id'=>$page_id])->one();
        if(!empty($page)){
            return $this->render("index",['page'=>$page]);
        }else{
            throw new NotFoundHttpException("没有找到该页面！");
        }
    }
}