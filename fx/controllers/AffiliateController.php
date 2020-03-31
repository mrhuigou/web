<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/3/31
 * Time: 14:58
 */

namespace fx\controllers;
use fx\models\AffiliateForm;
use Yii;
use yii\web\NotFoundHttpException;


class AffiliateController extends \yii\web\Controller{

    //申请成为分销商
    public function actionApply()
    {
        if (!$url = \Yii::$app->request->get('redirect')) {
            $url = Yii::$app->request->getAbsoluteUrl();
        }
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login', 'redirect' => $url]);
        }

        $model = new AffiliateForm();

        if ($model->load(\Yii::$app->request->post()) && $model->submit()) {
            throw new NotFoundHttpException("分销商申请成功，等待审核");
        } else {
            return $this->render('apply', [
                'model' => $model,
            ]);
        }


    }
}