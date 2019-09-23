<?php

namespace backend\controllers;

use api\models\V1\ClubTryCoupon;
use Yii;
use api\models\V1\ClubTry;
use api\models\V1\ClubTrySearch;
use api\models\V1\ClubTryUser;
use api\models\V1\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;
use yii\data\ActiveDataProvider;


/**
 * TryController implements the CRUD actions for ClubTry model.
 */
class TryController extends Controller
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
     * Lists all ClubTry models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClubTrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ClubTry model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'users' => $this->findTryusers($id),
        ]);
    }

    /**
     * Creates a new ClubTry model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ClubTry();

        if(Yii::$app->request->isPost){
         $post_data = Yii::$app->request->post();
         $product_code = $post_data['ClubTry']['product_code'];
         $product = Product::find()->where(['product_code'=>$product_code])->one();
         if(!$product){
            \Yii::$app->getSession()->setFlash('error', '请输入正确的商品编码');
            $model->load(Yii::$app->request->post());
            return $this->render('create', [
                'model' => $model,
            ]);
         }
         $model->product_id = $product->product_id;
         $model->product_base_id = $product->product_base_id;
         $model->creat_at = date('Y-m-d H:i:s');
         $model->update_at = date('Y-m-d H:i:s');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($coupons=Yii::$app->request->post("coupon")){
                foreach($coupons as $coupon){
                    if(!$item=ClubTryCoupon::findOne(['id'=>$coupon['id']])){
                        $item = new ClubTryCoupon();
                    }
                    $item->try_id = $model->id;
                    $item->coupon_id = $coupon['coupon_id'];
                    $item->save();
                }
            }



            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ClubTry model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->product_code = $model->product?$model->product->product_code:'';
        if(Yii::$app->request->post()){
         $post_data = Yii::$app->request->post();
         $product_code = $post_data['ClubTry']['product_code'];
         $product = Product::find()->where(['product_code'=>$product_code])->one();
         if(!$product){
            \Yii::$app->getSession()->setFlash('error', '请输入正确的商品编码');
            $model->load(Yii::$app->request->post());
             return $this->render('update', [
                'model' => $model,
            ]);
         }
         $model->product_id = $product->product_id;
         $model->product_base_id = $product->product_base_id;
         $model->update_at = date('Y-m-d H:i:s');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($coupons=Yii::$app->request->post("coupon")){
                foreach($coupons as $coupon){
                    if(!$item=ClubTryCoupon::findOne(['id'=>$coupon['id']])){
                        $item = new ClubTryCoupon();
                    }
                    $item->try_id = $model->id;
                    $item->coupon_id = $coupon['coupon_id'];
                    $item->save();
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ClubTry model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    public function actionDelCoupon(){
        ClubTryCoupon::findOne(['id'=>Yii::$app->request->post('id')])->delete();
        return ;
    }
    /**
     * Finds the ClubTry model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ClubTry the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ClubTry::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTryusers($id)
    {
        $users = new ActiveDataProvider([
            'query' => ClubTryUser::find()->where(['try_id'=>$id]),
        ]);
        if ($users  !== null) {
            return $users;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }


}
