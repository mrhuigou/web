<?php
namespace frontend\controllers;

use api\models\V1\Order;
use api\models\V1\OrderDigitalProduct;
use api\models\V1\OrderTotal;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;
class RechargeController extends Controller
{

    public function actionGetinfo(){
        $json=array();

        if(  Yii::$app->request->post('telephone') && Yii::$app->request->post('value') ){
            $data=array('telephone'=>Yii::$app->request->post('telephone'),'pervalue'=>Yii::$app->request->post('value'));
        }else{
            $json['result_code']='500';
            $json['err_msg']='参数错误！';
            $json['result_data']=array();
        }

        $result=$this->telquery($data);
        if(isset($result['retcode']) && $result['retcode']=='1'){
            $json['result_code']=$result['retcode'];
            $json['err_msg']='';
            $json['result_data']=array('price'=>$result['inprice'],'area'=>$result['game_area']);
        }elseif(isset($result['retcode']) && $result['retcode']=='11'){
            $json['result_code']=$result['retcode'];
            $json['err_msg']=$result['err_msg'];
            $json['result_data']=array();
        }else{
            $json['result_code']='500';
            $json['err_msg']='网络超时，请稍后重试';
            $json['result_data']=array();
        }
        echo json_encode($json);
        exit;
    }
    private function telquery($data){
        $url="http://api2.ofpay.com/telquery.do?";
        $data=array(
            'userid'=>'A1000769',
            'userpws'=>strtolower(md5("365@##jiarun")),
            'phoneno'=>$data['telephone'],
            'pervalue'=>$data['pervalue'],
            'version'=>'6.0'
        );
        $url.= http_build_query($data);

        $ch = curl_init(); //初始化CURL句柄
        curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $document = curl_exec($ch);//执行预定义的CURL
        curl_close($ch);
        $result=json_decode(json_encode(simplexml_load_string($document)),true);
        if($result['retcode']=='1'){
            $a=bcmul($result['inprice'],1.01,2);
            if(bccomp($a,$data['pervalue'])>=0){
                if(bccomp($result['inprice'],$data['pervalue'])>=0){
                    $result['inprice']=$data['pervalue'];
                }else{
                    $result['inprice']=bcadd($result['inprice'],0.05,2);
                }
            }else{
                $result['inprice']=$a;
            }
        }
        return $result;
    }
    public function actionCommitOrder(){
        $json=array();
        if( Yii::$app->request->post('telephone')  && Yii::$app->request->post('value') ){
            $data=array('telephone'=>Yii::$app->request->post('telephone'),'pervalue'=>Yii::$app->request->post('value'));
        }else{
            $json['result_code']='500';
            $json['err_msg']='请填写正确的电话号码';
            $json['result_data']=array();
        }
        if (Yii::$app->user->isGuest) {
            $json['result_code']='500';
            $json['err_msg']='对不起，您还未登陆，请先登陆后操作';
            $json['result_data']=array();
        }

        if(!$json){
            $result=$this->telquery($data);
            if(isset($result['retcode']) && $result['retcode']=='1'){
                //订单基础表
                $order_data=array(
                    'invoice_no'=>0,
                    'delivery_type'=>'1',
                    'order_type_code'=>'recharge',
                    'invoice_prefix'=>'INV-2014-00',
                    'platform_id'=>'1',
                    'platform_name'=>'智慧生活',
                    'platform_url'=>'http://www.365jiarun.com/',
                    'store_id'=> 1,
                    'store_name'=> '青岛家润店',
                    'store_url'=> '',
                    'customer_id'=>Yii::$app->user->getId(),
                    'customer_group_id'=>'1',
                    'firstname'=>Yii::$app->user->getIdentity()->firstname,
                    'lastname'=>'',
                    'email'=> Yii::$app->user->getIdentity()->email,
                    'telephone'=>Yii::$app->user->getIdentity()->telephone,
                    'gender'=>'',
                    'order_status_id'=>'1',
                    'comment'=>'',
                    'total'=>$result['inprice'],
                    'affiliate_id'=>'0',
                    'commission'=>'0',
                    'language_id'=>'2',
                    'currency_id'=>'4',
                    'currency_code'=>'CNY',
                    'currency_value'=>'1',
                    'in_cod'=>0,
                    'ip'=> Yii::$app->request->getUserIP(),
                    'user_agent'=>'',
                    'accept_language'=>'',
                    'invoice_temp'=>'不需要发票',
                    'invoice_title'=>'',
                    'delivery_type'=>0,
                    'use_date'=> '',
                    'time_range'=> '',
                    'use_nums'=> '',
                );

                if ( Yii::$app->request->getUserAgent()) {
                    $order_data['user_agent'] =  Yii::$app->request->getUserAgent();
                } else {
                    $order_data['user_agent'] = '';
                }


                if (Yii::$app->request->getPreferredLanguage()) {
                    $order_data['accept_language'] = Yii::$app->request->getPreferredLanguage();
                } else {
                    $order_data['accept_language'] = '';
                }
                $order_data['products']=array(
                    array(
                        'code'=>$result['cardid'],
                        'type'=>'telephone',
                        'name'=>$result['cardname'],
                        'account'=>$data['telephone'],
                        'model'=>'手机充值',
                        'qty'=>1,
                        'price'=>$data['pervalue'],
                        'total'=>$data['pervalue']
                    )
                );
                $order_data['totals']=array(
                    array(
                        'code'=>'sub_total',
                        'title'=>'商品总额',
                        'text'=>$data['pervalue'],
                        'sort_order'=>1,
                    ),
                    array(
                        'code'=>'total',
                        'title'=>'订单总额',
                        'text'=>$result['inprice'],
                        'sort_order'=>9,
                    )
                );

                $order_result = $this->addorder($order_data);
                $json['result_code']='1';
                $json['err_msg']='';
                $json['location']= Url::to(["payment/index",'trade_no'=>$order_result['order_id']],true);

            }else{
                $json['result_code']='500';
                $json['err_msg']='运营方正在维护';
                $json['result_data']=array();
            }
        }
        echo json_encode($json);
        exit;
    }
    private function addOrder($data){
        $order_no = date('YmdHis') . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $invoice_no = !empty($data['invoice_no']) ? $this->db->escape($data['invoice_no']) : '';
        $use_date = isset($data['use_date']) ? $data['use_date'] : '';
        $in_code = isset($data['in_cod']) ? $data['in_cod'] : 0;
        $time_range = isset($data['time_range']) ? $data['time_range'] : '';
        $delivery_type = isset($data['delivery_type']) ? $data['delivery_type'] : '1';
        $data['gender'] = isset($data['gender']) ? $data['gender'] : '';


        $model_order = new Order();
        $model_order->order_no =  $order_no;
        $model_order->order_type_code =  $data['order_type_code'];
        $model_order->invoice_no =  $invoice_no;
        $model_order->invoice_prefix =  $data['invoice_prefix'];
        $model_order->platform_id =  (int)$data['platform_id'];
        $model_order->platform_name =  $data['platform_name'];
        $model_order->platform_url =  $data['platform_url'];
        $model_order->store_id =  isset($data['store_id']) ? $data['store_id'] : 0;
        $model_order->store_name =  isset($data['store_name']) ? $data['store_name'] : '';
        $model_order->store_url =  isset($data['store_url']) ? $data['store_url'] : '';
        $model_order->customer_id =  (int)$data['customer_id'] ;
        $model_order->customer_group_id =  (int)$data['customer_group_id'];
        $model_order->firstname =  $data['firstname'];
        $model_order->lastname =  $data['lastname'];
        $model_order->email =  $data['email'];

        $model_order->telephone =  $data['telephone'];
        $model_order->gender =  $data['gender'];
        $model_order->order_status_id =  $data['order_status_id'];
        $model_order->comment =  $data['comment'];
        $model_order->total =  (float)$data['total'] ;
        $model_order->affiliate_id =  (int)$data['affiliate_id'];
        $model_order->commission =  (float)$data['commission'];
        $model_order->language_id =  (int)$data['language_id'];
        $model_order->currency_id =  (int)$data['currency_id'];
        $model_order->currency_code =  $data['currency_code'];
        $model_order->currency_value =  (float)$data['currency_value'];
        $model_order->ip =  $data['ip'];
        $model_order->user_agent =  $data['user_agent'];
        $model_order->accept_language =  $data['accept_language'];
        $model_order->invoice_temp =  $data['invoice_temp'];
        $model_order->invoice_title =  $data['invoice_title'];
        $model_order->delivery_type =  strval($delivery_type);
        $model_order->use_date =  $use_date;
        $model_order->time_range =  $time_range;
        $model_order->in_cod =  $in_code;
        $model_order->use_nums =  isset($data['use_nums']) ? $data['use_nums'] : '';
        $model_order->date_added =  date("Y-m-d H:i:s");
        $model_order->date_modified =  date("Y-m-d H:i:s");
        $model_order->save();
        $order_id = $model_order->order_id;
        if(isset($data['products']) && $data['products']){
            foreach($data['products'] as $value){
                $model_order_digital_product = new OrderDigitalProduct();
                $model_order_digital_product->order_id = $order_id;
                $model_order_digital_product->code = $value['code'];
                $model_order_digital_product->type = $value['type'];
                $model_order_digital_product->name = $value['name'];
                $model_order_digital_product->model = $value['model'];
                $model_order_digital_product->account = $value['account'];
                $model_order_digital_product->qty = $value['qty'];
                $model_order_digital_product->price = $value['price'];
                $model_order_digital_product->total = $value['total'];
                $model_order_digital_product->save();
            }
        }
        if(isset($data['totals']) && $data['totals']){
            foreach($data['totals'] as $total){
                $total['code_id'] = isset($total['code_id'])?$total['code_id']:0;
                $total['customer_code_id'] = isset($total['customer_code_id'])?$total['customer_code_id']:0;
                $model_order_total = new OrderTotal();
                $model_order_total->order_id = $order_id;
                $model_order_total->code = $total['code'];
                $model_order_total->title = $total['title'];
                $model_order_total->text = '￥'.number_format($total['text'],2);;
                $model_order_total->value = $total['text'];
                $model_order_total->sort_order = $total['sort_order'];
                $model_order_total->code_id = (int)$total['code_id'];
                $model_order_total->customer_code_id = (int)$total['customer_code_id'];
                $model_order_total->save();
            }
        }
        return array('order_id'=>$order_id,'total'=>$data['total']);
    }

}