<?php

namespace fx\controllers;

use api\models\V1\AffiliateScans;
use api\models\V1\AffiliateWeixinScans;
use Yii;
use yii\web\Controller;

/**
 * AffiliateController implements the CRUD actions for Affiliate model.
 */
class AffiliateScansController extends Controller
{

    /**
     * Lists all Affiliate models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        return $this->render('index');
    }


}
