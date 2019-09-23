<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/10/24
 * Time: 11:00
 */
namespace frontend\modules\club\controllers;
use dosamigos\qrcode\QrCode;
use yii\web\Controller;
class QrcodeController extends Controller{
    public function actionIndex(){
        $data=\Yii::$app->request->get('data');
        QrCode::png($data);
        exit;
    }
}