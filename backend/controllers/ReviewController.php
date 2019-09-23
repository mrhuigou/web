<?php
namespace backend\controllers;

use api\models\V1\Review;
use api\models\V1\ReviewReplay;
use api\models\V1\ReviewSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class ReviewController extends Controller
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
     * Lists all ReturnBase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ReviewSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdate(){
        $id = Yii::$app->request->get("id");
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) ) {
            $model->date_modified = date("Y-m-d H:i:s");
            $model->save();
            return $this->redirect("/review/index");
        }else{
            $provider = new ActiveDataProvider([
                'query' => Review::find()->where(['review_id'=>$id]),
            ]);
            return $this->render('update', ['model' => $model,'provider'=>$provider]);
        }


    }

    public function actionReplay(){
        $id = Yii::$app->request->get("id");
        $model = $this->findModel($id);
        $model_replay = ReviewReplay::find()->where(['review_id'=>$id])->one();
        if(empty($model_replay)){
            $model_replay = new ReviewReplay();
            $model_replay->date_added = date("Y-m-d H:i:s");
            $model_replay->server_user_id = 8;
        }
        if ($model_replay->load(Yii::$app->request->post()) ) {
            $model_replay->review_id = $id;
            $model_replay->date_modified = date("Y-m-d H:i:s");
            $model_replay->save();
        }

        return $this->render('replay', ['model' => $model,'model_replay'=>$model_replay]);
    }
    public function actionView(){
        $id = Yii::$app->request->get("id");
        $model = $this->findModel($id);
        $model_replay = ReviewReplay::find()->where(['review_id'=>$id])->one();

        return $this->render('view', ['model' => $model,'model_replay'=>$model_replay]);
    }
    protected function findModel($id)
    {
        if (($model = Review::findOne($id)) !== null) {
            return $model;
        } else {

            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
?>
