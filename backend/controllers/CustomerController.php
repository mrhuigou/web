<?php

namespace backend\controllers;

use api\models\V1\Coupon;
use api\models\V1\CustomerAuthentication;
use common\component\Notice\WxNotice;
use common\models\User;
use Yii;
use api\models\V1\Customer;
use api\models\V1\CustomerCoupon;
use api\models\V1\CustomerChest;
use api\models\V1\CustomerMap;
use api\models\V1\CustomerSearch;
use api\models\V1\CustomerTransaction;
use api\models\V1\Product;
use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\component\Curl\Curl;
use yii\helpers\Json;
use yii\data\ActiveDataProvider;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller
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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        if ($post = Yii::$app->request->post()) {
            switch ($post["type"]) {
                case 'tran':
                    $tran = new CustomerTransaction();
                    $tran->customer_id = $id;
                    $tran->description = $post["title"];
                    $tran->amount = $post["total"];
                    $tran->date_added = date("Y-m-d H:i:s");
                    $tran->save();
                    break;
                default:
                    break;
            }
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'trans' => $this->findTrans($id),
            'total_tran' => CustomerTransaction::find()->where(['customer_id' => $id])->sum("amount"),
            'coupons' => $this->findCoupons($id),
            'auth' => $this->findAuth($id),
        ]);
    }
	public function actionAddCoupon(){
    	try{
    		if(!$customer_id=Yii::$app->request->post('customer_id')){
			    throw new ErrorException('非法操作');
		    }
		    if(!$description=Yii::$app->request->post('description')){
			    throw new ErrorException('请填写描述');
		    }
		    if(!$coupons=Yii::$app->request->post('coupons')){
			    throw new ErrorException('请选择优惠券');
		    }
		    if($coupon_model=Coupon::find()->where(['coupon_id'=>$coupons])->all()){
			    $message=[];
		    	foreach ($coupon_model as $coupon){
				    $customer_model=new CustomerCoupon();
				    $customer_model->customer_id=$customer_id;
				    $customer_model->coupon_id=$coupon->coupon_id;
				    $customer_model->description = $description;
				    $customer_model->is_use = 0;
				    if ($coupon->date_type == 'DAYS') {
					    $customer_model->start_time = date('Y-m-d H:i:s', time());
					    $customer_model->end_time = date('Y-m-d 23:59:59', time() + $coupon->expire_seconds);
				    } else {
					    $customer_model->start_time = $coupon->date_start;
					    $customer_model->end_time = $coupon->date_end;
				    }
				    $customer_model->date_added = date('Y-m-d H:i:s', time());
				    $customer_model->save();
				    $message[]=[
					    'customer_id'=>$customer_id,
					    'url'=>'http://m.365jiarun.com/user-coupon/index',
					    'content'=>['title'=>'亲，恭喜您获得了'.$coupon->name,'name'=>$description,'content'=>$coupon->name."，已经存入你的账户。"]
				    ];
			    }
		    }else{
			    throw new ErrorException('优惠券不存在');
		    }
		    $this->sendMessage($message);
		    $data=['status'=>1,'message'=>'添加成功'];
	    }catch (ErrorException $e){
    		$data=['status'=>0,'message'=>$e->getMessage()];
	    }
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	protected function sendMessage($message=array()){
		if($message){
			foreach($message as $value){
				if($user=User::findIdentity($value['customer_id'])){
					if($open_id=$user->getWxOpenId()){
						$notice=new WxNotice();
						$notice->zhongjiang($open_id,$value['url'],$value['content']);
					}
				}
			}
		}
	}
    /**
     * Creates a new Customer model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionAuth_key()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        if($Model=User::findIdentity(Yii::$app->request->get('customer_id'))){
            if(!$Model->getAuthKey()){
                $Model->generateAuthKey();
                $Model->save();
            }
            return $this->redirect('https://m.365jiarun.com/site/backend?token='.$Model->getAuthKey());
        }
    }

    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect('/');
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->customer_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Customer model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
     public function actionCoupon($customer_coupon_id)
     {
         if (\Yii::$app->user->isGuest) {
             return $this->redirect('/');
         }
         CustomerCoupon::findOne($customer_coupon_id)->delete();

         return $this->redirect(Yii::$app->request->getReferrer());
     }

    public function actionMap()
    {
        // $model = CustomerMap::find()->select(['latitude','longitude'])->orderBy(['data_added'=>'desc'])->groupBy(['customer_id'])->limit(100)->asArray()->all();
        return $this->render('map', [
                // 'model' => $model,
            ]);
    }

    public function actionFind()
    {
        // $model = CustomerMap::find()->select(['latitude','longitude'])->orderBy(['data_added'=>'desc'])->groupBy(['customer_id'])->limit(100)->asArray()->all();
        $post = Yii::$app->request->post();
        $url = 'http://api.map.baidu.com/telematics/v3/local';
        $curl=new Curl();
        $result=$curl->get($url,["location"=>$post["lng"].','.$post["lat"],"keyWord"=>$post["keyword"],"tag" =>$post["tag"],"output"=>'json',"ak"=>"F3c76e3a3028c5a74c04b18bc09173aa"]);
        $data=Json::decode($result);
        return $result;
    }

    public function actionCheckProduct($code)
    {
        if($product = Product::findOne(['product_code'=>$code])){
            return $product->description->name;
        }else{
            return '商品不存在';
        }
    }

    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Customer::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function findTrans($id)
    {
        $trans = new ActiveDataProvider([
            'query' => CustomerTransaction::find()->where(['customer_id'=>$id])->orderBy('customer_transaction_id desc'),
        ]);
        if ( $trans !== null) {
            return $trans;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }
	protected function findAuth($id){
		$auth = new ActiveDataProvider([
			'query' => CustomerAuthentication::find()->where(['customer_id'=>$id])->orderBy('customer_authentication_id desc'),
		]);
		if ( $auth !== null) {
			return $auth;
		} else {
			// throw new NotFoundHttpException('The requested page does not exist.');
			return null;
		}
	}
    protected function findCoupons($id)
    {
        $coupons = new ActiveDataProvider([
            'query' => CustomerCoupon::find()->where(['customer_id'=>$id])->orderBy('customer_coupon_id desc'),
        ]);
        if ( $coupons !== null) {
            return $coupons;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }

    protected function findChests($id)
    {
        $chests = new ActiveDataProvider([
            'query' => CustomerChest::find()->where(['customer_id'=>$id])->orderBy('customer_chest_id desc'),
        ]);
        if ( $chests !== null) {
            return $chests;
        } else {
            // throw new NotFoundHttpException('The requested page does not exist.');
            return null;
        }
    }

    public function actionCouponAutoComplete(){
	    $data=[];
	    $coupon_model=[
	    	'OTHER'=>'其它',
		    'ORDER'=>'订单券',
		    'BUY_GIFTS'=>'买赠券',
		    'BRAND'=>'品牌券',
		    'CLASSIFY'=>'分类券',
		    'PRODUCT'=>'商品券'
	    ];
	    if($query=Yii::$app->request->get('term')){
			if($filer_datas=Coupon::find()->where(['like','name',$query])->orWhere(['like','code',$query])->andWhere(['status'=>1])->limit(10)->all()){
				foreach ($filer_datas as $value){
					$data[]=[
						'value'=>$value->coupon_id,
						'label'=>"[".$value->code."]---".$value->name."---".$coupon_model[$value->model?$value->model:"OTHER"],
					];
				}
			}
	    }
	    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
	    return $data;
	}
}
