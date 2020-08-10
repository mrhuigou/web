<?php

namespace api\controllers\wd;

use \yii\web\Controller;
use yii\web\Response;

class WdController extends Controller
{
    public function actionIndex()
    {
        \Yii::$app->response->format=Response::FORMAT_JSON;
        return array('status'=>'success');
    }
}
