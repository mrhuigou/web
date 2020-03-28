<?php

namespace fx\controllers;

use affiliate\models\AffiliateForm;
use Yii;
use api\models\V1\Affiliate;
use api\models\V1\AffiliateSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

/**
 * AffiliateController implements the CRUD actions for Affiliate model.
 */
class AffiliateController extends Controller
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

    /**
     * Lists all Affiliate models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $searchModel = new AffiliateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Affiliate model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model=Affiliate::findOne(['affiliate_id'=>$id,'parent_id'=>Yii::$app->user->getId()]);
        return $this->render('view', [
            'model' =>$model,
        ]);
    }

    /**
     * Creates a new Affiliate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model = new AffiliateForm();
        $model->setScenario('create');
         if ($model->load(Yii::$app->request->post()) && $data=$model->save()) {
            return $this->redirect(['view', 'id' => $data->affiliate_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Affiliate model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model = new AffiliateForm($id);
        $model->setScenario('update');
        if (Yii::$app->request->post() && $data=$model->save()) {
            return $this->redirect(['view', 'id' => $data->affiliate_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Affiliate model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model=Affiliate::findOne(['affiliate_id'=>$id,'parent_id'=>Yii::$app->user->getId()]);
        $model->delete();
        return $this->redirect(['index']);
    }


}
