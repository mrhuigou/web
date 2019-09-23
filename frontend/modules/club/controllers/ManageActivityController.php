<?php
namespace frontend\modules\club\controllers;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
class ManageActivityController extends Controller
{
    public function actionIndex()
    {

        return $this->render('index');
    }
    public function actionCreate()
    {

        return $this->render('create');
    }
}
