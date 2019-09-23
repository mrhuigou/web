<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/2/12
 * Time: 17:02
 */

namespace api\controllers\chat;
use common\component\image\Image;
use Yii;
use api\models\V1\Customer;
use api\models\V1\CustomerFootprint;
use api\models\V1\Order;
use api\models\V1\OrderShipping;
use api\models\V1\OrderStatus;
use api\models\V1\OrderPayment;
use api\models\V1\OrderProduct;
use api\models\V1\OrderHistory;
use api\models\V1\OrderTotal;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use api\models\V1\ProductBaseDescription;
use api\models\V1\Store;
use yii\data\ActiveDataProvider;
use common\component\response\Result;
use yii\web\NotFoundHttpException;

class InterfaceController extends \yii\rest\Controller{
    public function actionIndex(){
        $customer_id=\Yii::$app->request->get('customer_id');
        // $store_code=\Yii::$app->request->get('store_code');
        $customer=Customer::findOne(['customer_id'=>$customer_id]);
        // $store=Store::findOne(['store_code'=>$store_code]);

        $result=[];
        if($customer){
          $result['customer']=$customer;
          $result['customer']['photo']=$customer->photo ? Image::resize($customer->photo,100,100):"http://www.365jiarun.com/image/life/avatar_b.jpg";
          $result['customer']['password']='';
          $result['customer']['salt']='';
          $result['customer']['idcard']='';
          $result['customer']['password_reset_token']='';
          $result['customer']['forget_link_validity']='';
          $result['customer']['paymentpwd']='';
          $result['customer']['psalt']='';

            //$order_history=Order::find()->where(['customer_id'=>$customer->customer_id,'store_id'=>$store->store_id])->orderBy('date_added desc')->limit(10)->all();
            // $primaryConnection = \Yii::$app->db;
            // $command = $primaryConnection->createCommand('select * FROM jr_order LEFT JOIN jr_order_product ON jr_order.order_id = jr_order_product.order_id WHERE jr_order.customer_id = :customer_id ORDER BY jr_order.date_added desc LIMIT 10');
            // $command->bindValues([':customer_id'=>$customer->customer_id]);
            // $order_history = $command->queryAll();
            // if($order_history){
            //     foreach($order_history as $key=>$order){
            //         $shipping=OrderShipping::findOne(['order_id'=>$order['order_id']]);
            //         $orderstatus=OrderStatus::findOne(['order_status_id'=>$order['order_status_id']]);
            //         $result['orders'][$key]=[
            //             'order_id'=>$order['order_id'],
            //             'order_number'=>$order['order_no'],
            //             'total'=>$order['total'],
            //             'data_added'=>$order['date_added'],
            //             'shipping'=>$shipping?[ 'shipping_address'=>$shipping->shipping_country.'-'.$shipping->shipping_zone.'-'.$shipping->shipping_city."-".$shipping->shipping_address_1,
            //                             'shipping_telephone'=>$shipping->shipping_telephone,
            //                             'shipping_username'=>$shipping->shipping_firstname
            //                           ]:[],
            //             'order_status'=>$orderstatus?$orderstatus->name:"",
            //         ] ;
            //     }
            // }
        }else{
            $result['customer'] = new \ArrayObject();
        }
        // $data = $this->renderPartial('/chat/index',['data'=>$result]);
        $data = $result;
        if(Yii::$app->request->get('callback')){
            Yii::$app->getResponse()->format ="jsonp";
            return array(
                'data'     => $data,
                'callback' => \Yii::$app->request->get('callback')
            );
        }else{
            Yii::$app->getResponse()->format ="json";
            return ['data'=>$data];
            // return Result::OK($data);
        }
    }

    public function actionView()
    {
        $customer_id=\Yii::$app->request->get('customer_id');
        $store_code=\Yii::$app->request->get('store_code');
        $per_page = 5;
        $page=intval(\Yii::$app->request->get('page'))?intval(\Yii::$app->request->get('page')):1;
        $offset = $page*$per_page-$per_page;
        $store_info = Store::findOne(['store_code'=>$store_code]);
        if(!$store_info){
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $orders = Order::find()->where(['customer_id'=>$customer_id,'store_id'=>$store_info->store_id])->orderBy(['date_added'=>SORT_DESC])->offset($offset)->limit($per_page)->all();
        // $data = $this->renderPartial('/chat/view', [
        //     'model' => $this->findModel($id),
        //     'payment' => $this->findPayment($id),
        //     'shipping' => $this->findShipping($id),
        //     'total' => $this->findTotal($id),
        //     'products' => $this->findProducts($id),
        //     'history' => $this->findHistory($id),
        // ]);
        $data = [];

        if(!$orders){
            $data['order'] = [];
        }else{
            foreach ($orders as $key => $value) {
                $data['order'][] = [
                    'model' => $this->findModel($value->order_id),
                    'payment' => $this->findPayment($value->order_id),
                    'shipping' => $this->findShipping($value->order_id),
                    'total' => $this->findTotal($value->order_id),
                    'products' => $this->findProducts($value->order_id),
                    // 'history' => $this->findHistory($value->order_id),
                ];
            }
        }

        $data['total_count'] = Order::find()->where(['customer_id'=>$customer_id,'store_id'=>$store_info->store_id])->count();
        $data['total_page'] = ceil($data['total_count']/$per_page);

        if(Yii::$app->request->get('callback')){
            Yii::$app->getResponse()->format ="jsonp";
            return array(
                'data'     => $data,
                'callback' => \Yii::$app->request->get('callback')
            );
        }else{
            Yii::$app->getResponse()->format ="json";
            return ['data'=>$data];
        }
    }

    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            $param_arr = ['order_id','order_type_code','order_no','invoice_no','invoice_prefix','customer_id','firstname','email','telephone','gender','payment_method','payment_code','total','comment','order_status_id','date_added','invoice_temp','invoice_title'];
            $order_type = array('normal'=>'普通订单','presell'=>'预售订单','voucher'=>'礼品券','restaurant'=>'订餐订单','takeaway'=>'外卖订单','recharge'=>'充值订单','virtual'=>'虚拟订单','ACTIVITY'=>'活动订单');
            $model_new = [];
            foreach ($model as $key => $value) {
                if(in_array($key,$param_arr)){
                    $model_new[$key] = $value;
                }
            }
            $model_new['order_type_code'] = $order_type[$model->order_type_code];
            $model_new['order_status_id'] = $model->orderStatus->name;
            $model_new['total'] = number_format($model_new['total'],2);
            return $model_new;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findPayment($id)
    {
        if (($payments = OrderPayment::findAll(['order_id'=>$id])) !== null) {
            $param_arr = ['payment_method','total','date_added'];
            $payment_new = [];
            foreach ($payments as $index =>$payment) {
                foreach ($payment as $key => $value) {
                    if(in_array($key,$param_arr)){
                        $payment_new[$index][$key] = $value;
                    }
                }
                $payment_new[$index]['total'] = number_format($payment_new[$index]['total'],2);
            }
            return $payment_new;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return [];
        }
    }

    protected function findShipping($id)
    {
        if (($shipping = OrderShipping::findOne(['order_id'=>$id])) !== null) {
            $param_arr = ['shipping_firstname','shipping_telephone','shipping_postcode','shipping_address_1','shipping_city','shipping_district','shipping_method','delivery','delivery_time'];
            $shipping_new = [];
            foreach ($shipping as $key => $value) {
                    if(in_array($key,$param_arr)){
                        $shipping_new[$key] = $value;
                    }
                }
            return $shipping_new;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return new \ArrayObject();
        }
    }

    protected function findTotal($id)
    {
        if (($totals = OrderTotal::findAll(['order_id'=>$id])) !== null) {
             $param_arr = ['title','value','sort_order'];
             $total_new = [];
             foreach ($totals as $index =>$total) {
                 foreach ($total as $key => $value) {
                     if(in_array($key,$param_arr)){
                         $total_new[$index][$key] = $value;
                     }
                 }
                 $total_new[$index]['value'] = number_format($total_new[$index]['value'],2);
             }
            return $total_new;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return [];
        }
    }

    protected function findProducts($id)
    {
        // $products = new ActiveDataProvider([
        //     'query' => OrderProduct::find()->where(['order_id'=>$id]),
        // ]);
        if (($products = OrderProduct::findAll(['order_id'=>$id]))  !== null) {
            $param_arr = ['product_code','name','quantity','price','total','unit','format'];
            $product_new = [];
            foreach ($products as $index =>$product) {
                 foreach ($product as $key => $value) {
                     if(in_array($key,$param_arr)){
                         $product_new[$index][$key] = $value;
                     }
                 }
                 $product_new[$index]['image'] = \common\component\image\Image::resize($product->product->image,45,55);
                 $product_new[$index]['price'] = number_format($product_new[$index]['price'],2);
                 $product_new[$index]['total'] = number_format($product_new[$index]['total'],2);
             }
            return $product_new;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return [];
        }
    }

    protected function findHistory($id)
    {
        // $history = new ActiveDataProvider([
        //     'query' => OrderHistory::find()->where(['order_id'=>$id]),
        // ]);
        if (($history = OrderHistory::findAll(['order_id'=>$id]))  !== null) {
            return $history;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return [];
        }
    }
}