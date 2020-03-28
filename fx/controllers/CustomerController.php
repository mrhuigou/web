<?php

namespace fx\controllers;

use Yii;
use affiliate\models\CustomerSearch;
use yii\web\Controller;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class CustomerController extends Controller
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
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
