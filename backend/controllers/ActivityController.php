<?php

namespace backend\controllers;

use api\models\V1\ActivitySearch;
use api\models\V1\ClubActivityItems;
use api\models\V1\ClubActivityKv;
use api\models\V1\ClubActivityUser;
use api\models\V1\ClubActivityAdmin;
use common\models\User;
use Yii;
use api\models\V1\ClubActivity;
use api\models\V1\ClubActivitySearch;
use api\models\V1\Customer;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;
/**
 * BookController implements the CRUD actions for ClubActivity model.
 */
class ActivityController  extends Controller
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
                'path' => '@backend/web/upload/images',
                'uploadOnlyImage' =>false
                
            ],
        ];
    }

    /**
     * Lists all ClubActivity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClubActivity model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'users' => $this->findActivityusers($id),
        ]);
    }

    /**
     * Creates a new ClubActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClubActivity();

        if ($model->load(Yii::$app->request->post())) {
            $model->creat_at = date("Y-m-d H:i:s");
            $model->update_at = date("Y-m-d H:i:s");
            $model->save();
            if($model->fee){
                if(Yii::$app->request->post("items")){
                    $items = Yii::$app->request->post("items");
                    foreach($items as $item){
                        if(!$activity_items=ClubActivityItems::findOne(['id'=>$item['id']])){
                            $activity_items = new ClubActivityItems();
                        }
                        $activity_items->activity_id = $model->id;
                        $activity_items->fee = $item['fee'];
                        $activity_items->name = $item['name'];
                        $activity_items->quantity = $item['quantity'];
                        $activity_items->status = 1;
                        $activity_items->save();
                    }
                }
            }
            if(Yii::$app->request->post("userItems")){
                $userItems = Yii::$app->request->post("userItems");
                foreach($userItems as $item){
                    if(!$activity_kv=ClubActivityKv::findOne($item['id'])){
                        $activity_kv = new ClubActivityKv();
                    }
                    $activity_kv->activity_id = $model->id;
                    $activity_kv->title = $item['title'];
                    //$activity_kv->name = $item['name'];
                    $activity_kv->type = 'input';
                    $activity_kv->is_require = 1;
                    $activity_kv->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClubActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
/**/
        if ($model->load(Yii::$app->request->post())) {
            $model->update_at = date("Y-m-d H:i:s");
            $model->save();
            if($model->fee){
                if(Yii::$app->request->post("items")){
                    $items = Yii::$app->request->post("items");
                    foreach($items as $item){
                        if(!$activity_items=ClubActivityItems::findOne(['id'=>$item['id']])){
                            $activity_items = new ClubActivityItems();
                        }
                        $activity_items->activity_id = $model->id;
                        $activity_items->fee = $item['fee'];
                        $activity_items->name = $item['name'];
                        $activity_items->quantity = $item['quantity'];
                        $activity_items->status = 1;
                        $activity_items->save();
                    }
                }
            }

            if(Yii::$app->request->post("userItems")){
                $userItems = Yii::$app->request->post("userItems");
                foreach($userItems as $item){
                    if(!$activity_kv=ClubActivityKv::findOne($item['id'])){
                        $activity_kv = new ClubActivityKv();
                    }
                    $activity_kv->activity_id = $model->id;
                    $activity_kv->title = $item['title'];
                   // $activity_kv->name = $item['name'];
                    $activity_kv->type = 'input';
                    $activity_kv->is_require = 1;
                    $activity_kv->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing ClubActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {
    //     $this->findModel($id)->delete();

    //     return $this->redirect(['index']);
    // }

    public function actionAdministrator($id)
    {
        $model = new ActiveDataProvider([
            'query' => ClubActivityAdmin::find()->where(['activity_id'=>$id]),
        ]);
        $activity = ClubActivity::findOne([$id]);

        return $this->render('administrator', [
            'model'=>$model,
            'activity'=>$activity
        ]);
    }

    public function actionSearchCustomer()
    {
        $keyword = Yii::$app->request->post("keyword");
        $id = Yii::$app->request->post("id");
        $model = User::findByUsername($keyword);
        
        if($model){
            $result = '<div class="well">昵称：'.$model->nickname.'，电话：'.$model->telephone.'，邮箱：'.$model->email.' <a class="btn btn-primary btn-sm" href="'.\yii\helpers\Url::to(['add-administrator','id'=>$id,'customer_id'=>$model->customer_id],true).'">加入</a></div>';
        }else{
            $result = 'wrong';
        }

        return $result;
    }

    public function actionAddAdministrator($id,$customer_id)
    {
        if(!$record = ClubActivityAdmin::findOne(['activity_id'=>$id,'customer_id'=>$customer_id])){
            $record = new ClubActivityAdmin();
            if(ClubActivity::findOne($id) && Customer::findOne($customer_id)){
                $record->activity_id = intval($id);
                $record->customer_id = intval($customer_id);
                $record->save();
            }
        }
        return $this->redirect(\yii\helpers\Url::to(['administrator','id'=>$id],true));
    }

    public function actionRemoveAdministrator($id,$customer_id)
    {
        if($record = ClubActivityAdmin::findOne(['activity_id'=>$id,'customer_id'=>$customer_id])){
            $record->delete();          
        }
        return $this->redirect(\yii\helpers\Url::to(['administrator','id'=>$id],true));
    }

    public function actionRecommend($id)
    {
        if($model = $this->findModel($id)){
            if($model->is_recommend == 1){
                $model->is_recommend = 0;
                $model->save();
            }else{
                $model->is_recommend = 1;
                $model->save();
            }
        }
        return $this->redirect(\yii\helpers\Url::to(['index'],true));
    }

    /**
     * Finds the ClubActivity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubActivity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubActivity::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionDelItem(){
        if(Yii::$app->request->post("id")){
            $item_id = Yii::$app->request->post("id");
            $clubactivityItems = ClubActivityItems::findOne(['id'=>$item_id]);
            $clubactivityItems->status = 0;
            return "success";
        }else{
            return "false";
        }
    }
    public function actionDelUserItem(){
        if(Yii::$app->request->post("id")){
            $item_id = Yii::$app->request->post("id");
            ClubActivityKv::deleteAll(['club_activity_kv_id'=>$item_id]);
            return "success";
        }else{
            return "false";
        }
    }
    protected function findActivityusers($id)
    {
        $users = new ActiveDataProvider([
            'query' => ClubActivityUser::find()->where(['activity_id'=>$id]),
        ]);
        if ($users  !== null) {
            return $users;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }
}
