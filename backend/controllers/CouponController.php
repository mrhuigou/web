<?php

namespace backend\controllers;

use Yii;
use api\models\V1\Coupon;
use api\models\V1\Product;
use api\models\V1\CategoryDescription;
use api\models\V1\CouponCategory;
use api\models\V1\CouponProduct;
use api\models\V1\CouponSearch;
use backend\models\CouponcateForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\extensions\widgets\fileapi\actions\UploadAction as FileAPIUpload;
use yii\data\ActiveDataProvider;

/**
 * CouponController implements the CRUD actions for Coupon model.
 */
class CouponController extends Controller
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
            'fileapi-upload' => [
                'class' => FileAPIUpload::className(),
                'path' => '@backend/web/upload/images'
            ],
        ];
    }

    /**
     * Lists all Coupon models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouponSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coupon model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Coupon model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Coupon();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->date_added = date('Y-m-d H:i:s',time());
            $model->save();
            return $this->redirect(['view', 'id' => $model->coupon_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Coupon model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $prods = $this->findProds($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->coupon_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'products'=>$prods,
            ]);
        }
    }


    /**
     * Deletes an existing Coupon model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }


    public function actionDeleteProduct($id)
    {
        $cp = CouponProduct::findOne($id);
        if($cp !== null){
            $cp->delete();
        }
        return $this->redirect(['update', 'id' => $cp->coupon_id,'#'=>'tab_6_2']);
    }

    public function actionAddProduct()
    {
        $post_data = Yii::$app->request->post();
        $c = Coupon::findOne($post_data["id"]);
        $p = Product::find()->where(['product_code'=>$post_data["product_code"]])->one();
        if($c !== null && $p !== null){
            $cp = new CouponProduct();
            $cp->coupon_id = $post_data["id"];
            $cp->product_id = $p->product_id;
            $cp->save();
            return true;
        }else{
            return 'wrong';
        }
    }


    /**
     * Finds the Coupon model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Coupon the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coupon::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    protected function findProds($id)
    {
        $model = new ActiveDataProvider([
            'query' => CouponProduct::find()->where(['coupon_id'=>$id]),
        ]);
        if ($model  !== null) {
            return $model;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }
}
