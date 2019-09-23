<?php

namespace backend\controllers;

use api\models\V1\ExpressCardProduct;
use api\models\V1\ExpressCardView;
use api\models\V1\LegalPerson;
use api\models\V1\Product;
use common\models\UploadForm;
use Yii;
use api\models\V1\ExpressCard;
use api\models\V1\ExpressCardSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ExpressCardController implements the CRUD actions for ExpressCard model.
 */
class ExpressCardController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ExpressCard models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpressCardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpressCard model.
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
     * Creates a new ExpressCard model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExpressCard();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ExpressCard model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ExpressCard model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ExpressCard model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExpressCard the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExpressCard::findOne($id)) !== null) {
            $legal_person = $model->legalPerson;
            $model->legal_person_name = $legal_person->name;
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionGetProducts(){
        $filter_code = Yii::$app->request->get('term');
        $data = [];
        if($product_data=Product::find()->where(['like','product_code',$filter_code.'%',false])->orderBy('product_id asc')->limit(10)->all()){
            foreach($product_data as $value){
                $data[]=[
                    'value'=>$value->product_code,
                    'label'=>$value->product_code."---".$value->description->name,
                ];
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }
    public function actionGetProduct(){
        $filter_code = Yii::$app->request->post("product_code");
        $product = Product::findOne(['product_code'=>$filter_code]);
        $return = [];
        if($product){
            $return['status'] = true;
            $return['shop_code'] = $product->store_code;
            $return['product_base_code'] = $product->product_base_code;
            $return['product_code'] = $product->product_code;
            $return['product_name'] = $product->description->name;
        }else{
            $return['status'] = false;
            $return['message'] = '商品不存在';
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $return;
    }
    public function actionGetLegalPerson(){
        $filter_code = Yii::$app->request->get("term");

        $legal_persons = LegalPerson::find()->where(['like','name',$filter_code])->limit(10)->all();
        $data = [];
        foreach($legal_persons as $legal_person){
            $data[]=[
                'value'=>$legal_person->legal_person_id,
                'label'=>$legal_person->name,
            ];
        }

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $data;
    }


    public function actionImportProduct(){
        $express_card_id = Yii::$app->request->get("express_card_id");
        if($express_card_id){
            $express_card = ExpressCard::findOne(['id'=>$express_card_id]);
            if($express_card){
                $model = new UploadForm();

                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        ExpressCardProduct::deleteAll(['express_card_id'=>$express_card_id]);

                        if ($model->file && $model->validate()) {
                            $fileName = $model->file->tempName;
                            $data = \moonland\phpexcel\Excel::widget([
                                'mode' => 'import',
                                'fileName' => $fileName,
                                'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
                                'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
                                'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                            ]);
                            $insert_data = [];
                            if($data){
                                $express_card_product = new ExpressCardProduct();
                                foreach ($data as $xproduct){
                                    $product = Product::findOne(['product_code'=>$xproduct['product_code']]);
                                    if($product){
                                        $insert_data['product_code'] = $product->product_code;
                                        $insert_data['shop_code'] = $product->store_code;
                                        $insert_data['product_base_code'] = $product->product_base_code;
                                        $insert_data['product_name'] = $product->description->name;
                                        $insert_data['quantity'] = $xproduct['quantity'];
                                        $insert_data['description'] = $xproduct['description'];
                                        $insert_data['status'] = $xproduct['status'];
                                        $insert_data['express_card_id'] = $express_card_id;

                                        $express_card_product->isNewRecord(true);
                                        $express_card_product->setAttributes($insert_data);
                                        $express_card_product->save();
                                        $express_card_product->id = 0;
                                    }else{
                                        throw new Exception("商品信息不存在");
                                    }

                                }
                            }
                        }


                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw new NotFoundHttpException($e->getMessage());
                    }

                }

                return $this->render('upload', ['model' => $model,'type'=>'impPro']);

            }
        }
    }
    public function actionImportView(){
        $express_card_id = Yii::$app->request->get("express_card_id");
        if($express_card_id){
            $express_card = ExpressCard::findOne(['id'=>$express_card_id]);
            if($express_card){
                $model = new UploadForm();

                if (Yii::$app->request->isPost) {
                    $model->file = UploadedFile::getInstance($model, 'file');
                    $transaction = Yii::$app->db->beginTransaction();
                    try{
                        if ($model->file && $model->validate()) {
                            $fileName = $model->file->tempName;
                            $data = \moonland\phpexcel\Excel::widget([
                                'mode' => 'import',
                                'fileName' => $fileName,
                                'setFirstRecordAsKeys' => true, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
                                'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
                                'getOnlySheet' => 'sheet1', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                            ]);
                            if($data){
                                foreach ($data as $xcard){
                                    if($xcard){
                                        $express_card_view = ExpressCardView::findOne(['card_no'=>$xcard['card_no'],'express_card_id'=>$express_card_id]);
                                        if(!$express_card_view){
                                            //新增卡信息
                                            $express_card_view = new  ExpressCardView();
                                            $express_card_view->express_card_id = $express_card_id;
                                            $express_card_view->card_no = $xcard['card_no'];
                                            $express_card_view->card_pwd = $xcard['card_pwd'];
                                            $express_card_view->status = $xcard['status'];
                                            $express_card_view->save();
                                        }else{
                                            //更新卡信息
                                            $express_card_view->card_pwd = $xcard['card_pwd'];
                                            $express_card_view->status = $xcard['status'];
                                            $express_card_view->save();
                                        }
                                    }
                                }
                            }
                        }


                        $transaction->commit();
                    } catch (\Exception $e) {
                        $transaction->rollBack();
                        throw new NotFoundHttpException($e->getMessage());
                    }

                }

                return $this->render('upload', ['model' => $model,'type'=>'impPro']);

            }
        }
    }
}
