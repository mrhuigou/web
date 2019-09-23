<?php
namespace h5\controllers;
use api\models\V1\CustomerCollect;
use api\models\V1\ProductBase;
use common\component\Track\Track;
use console\models\Store;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class CollectController extends \yii\web\Controller {

    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if (\Yii::$app->request->get("type") == 2) {
            $type = 2;
        } else {
            $type = 1;
        }
        $model = new CustomerCollect();
        $dataProvider = new ActiveDataProvider([
            'query' => $model->find()->where(['customer_id' => \Yii::$app->user->getId(), 'type_id' => $type])->orderBy('date_added DESC'),
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider, 'model' => $model,'type'=>$type]);
    }

    public function actionAdd()
    {
        $json = [];
        if (\Yii::$app->user->isGuest) {
            $json['message'] = '请您先登录';
        } else {
            $item_id = \Yii::$app->request->post("item_id");
            $type = \Yii::$app->request->post("type");
            if ($type == 'product') {
                if (!$customer_collect = CustomerCollect::findOne(['customer_id' => \Yii::$app->user->getId(), 'product_base_id' => $item_id, 'type_id' => 1])) {
                    if ($product = ProductBase::findOne(['product_base_id' => $item_id])) {
                        $new_customer_collect = new CustomerCollect();
                        $new_customer_collect->customer_id = \Yii::$app->user->getId();
                        $new_customer_collect->platform_id = '1';
                        $new_customer_collect->platform_code = 'PT0001';
                        $new_customer_collect->product_base_id = $product->product_base_id;
                        $new_customer_collect->product_base_code = $product->product_base_code;
                        $new_customer_collect->store_id = $product->store_id;
                        $new_customer_collect->store_code = $product->store_code;
                        $new_customer_collect->type_id = 1;
                        $new_customer_collect->date_added = date("Y-m-d H:i:s");
                        $new_customer_collect->save();
                    }
                    Track::add($product->product_base_id, 'collect');
                    $json['message'] = '收藏成功';

                } else {
                    $json['message'] = '已经收藏过了';
                }
            } elseif ($type == 'store') {
                if (!$customer_collect = CustomerCollect::findOne(['customer_id' => \Yii::$app->user->getId(), 'product_base_id' => $item_id, 'type_id' => 2])) {
                    if ($store = Store::findOne(['store_id' => $item_id])) {
                        $new_customer_collect = new CustomerCollect();
                        $new_customer_collect->customer_id = \Yii::$app->user->getId();
                        $new_customer_collect->platform_id = '1';
                        $new_customer_collect->platform_code = 'PT0001';
                        $new_customer_collect->store_id = $store->store_id;
                        $new_customer_collect->store_code = $store->store_code;
                        $new_customer_collect->type_id = 2;
                        $new_customer_collect->date_added = date("Y-m-d H:i:s");
                        $new_customer_collect->save();
                    }
                    $json['message'] = '收藏成功';
                } else {
                    $json['message'] = '已经收藏过了';
                }
            }else{
                $json['message'] = '参数错误';
            }
        }
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $json;
    }
    public function actionCancel(){
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/site/login');
        }
        if($model=CustomerCollect::findOne(['customer_collect_id'=>\Yii::$app->request->get('id')])){
            $model->delete();
            return $this->redirect('index');
        }else{
            throw new NotFoundHttpException('没有找到相关信息');
        }
    }

}
