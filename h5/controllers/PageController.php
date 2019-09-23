<?php
namespace h5\controllers;

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
			if (\Yii::$app->user->isGuest) {
	return $this->redirect(['/site/login','redirect'=>\Yii::$app->request->getAbsoluteUrl()]);
	}

        $page_id = Yii::$app->request->get("page_id");
        $page = Page::find()->where(['page_id'=>$page_id])->one();
        if(!empty($page)){
//            $referer = Yii::$app->request->referrer;
//            if(strpos($referer,'weixinbridge')){
//                //Yii::$app->request->absoluteUrl;
//                $_SERVER['HTTP_REFERER'] = 'https://m.365jiarun.com/';
//                $this->redirect('https://m.365jiarun.com/page/'.$page_id.'.html');
//            }
            return $this->render("index",['page'=>$page]);
        }else{
            throw new NotFoundHttpException("没有找到该页面！");
        }
    }
}