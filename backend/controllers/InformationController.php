<?php

namespace backend\controllers;

use Yii;
use api\models\V1\Information;
use api\models\V1\InformationDescription;
use api\models\V1\InformationDescriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;

/**
 * InformationController implements the CRUD actions for InformationDescription model.
 */
class InformationController extends Controller
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

    public function actions()
    {
        return [
            'Kupload' => [
                'class' => 'common\extensions\widgets\kindeditor\KindEditorAction',
            ],
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@backend/web/upload/images'
            ],
        ];
    }

    /**
     * Lists all InformationDescription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InformationDescriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InformationDescription model.
     * @param integer $information_id
     * @param integer $language_id
     * @return mixed
     */
    // public function actionView($information_id)
    // {
    //     return $this->render('view', [
    //         'model' => $this->findModel($information_id),
    //     ]);
    // }

    /**
     * Creates a new InformationDescription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InformationDescription();
        $model2 = new Information();

        if ( $model2->load(Yii::$app->request->post()) && $model2->save()) {
            $model->information_id = $model2->information_id;
            $model->language_id = 2;
            $model->date_added = date('Y-m-d H:i:s',time());
            $model->date_modified = date('Y-m-d H:i:s',time());
            if($model->load(Yii::$app->request->post()) && $model->save()){
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'model2' => $model2,
            ]);
        }
    }

    /**
     * Updates an existing InformationDescription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $information_id
     * @param integer $language_id
     * @return mixed
     */
    public function actionUpdate($information_id)
    {
        $model = $this->findModel($information_id);
        $model2 = Information::findOne(['information_id' => $information_id]);
        $model->date_modified = date('Y-m-d H:i:s',time());
        if ($model->load(Yii::$app->request->post()) && $model->save() && $model2->load(Yii::$app->request->post()) && $model2->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'model2' => $model2,
            ]);
        }
    }

    /**
     * Deletes an existing InformationDescription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $information_id
     * @param integer $language_id
     * @return mixed
     */
    public function actionDelete($information_id)
    {
        $this->findModel($information_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the InformationDescription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $information_id
     * @param integer $language_id
     * @return InformationDescription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($information_id)
    {
        if (($model = InformationDescription::findOne(['information_id' => $information_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
