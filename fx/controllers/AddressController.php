<?php
namespace fx\controllers;
use api\models\V1\Customer;
use api\models\V1\RechargeHistory;
use common\component\Curl\Curl;
use fx\models\AddressForm;
use Yii;
use api\models\V1\Address;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AddressController extends \yii\web\Controller {
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

	public function actionIndex()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/address/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
        $in_range = Yii::$app->request->get("in_range");
        if($in_range == 1){
            $model = Address::find()->where(['customer_id' => \Yii::$app->user->identity->getId(),'not_in_range'=>0])->orderBy('address_id')->all();
        }else{
            $model = Address::find()->where(['customer_id' => \Yii::$app->user->identity->getId()])->orderBy('address_id')->all();
        }

		if ($model) {
			if (Yii::$app->request->isPost && $address = Address::findOne(['customer_id' => \Yii::$app->user->identity->getId(), 'address_id' => Yii::$app->request->post('address_id')])) {
                if($in_range == 1){
                    Yii::$app->session->set('checkout_address_id', $address->address_id);
                }else{
                    Yii::$app->session->set('checkout_express_address_id', $address->address_id);
                }
				return $this->redirect($url);
			}
			return $this->render('index', ['address' => $model]);
		} else {
			return $this->redirect(['/address/create', 'redirect' => $url]);
		}
	}

	public function actionCreate()
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/address/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
        $all_range = false;
        if(Yii::$app->request->get('range') == 'all_range'){
		    $all_range = true;
        }

		$model = new AddressForm();
        $model->has_other_zone = true;
        if($all_range){
            $model->in_range = 0;
        }else{
            $model->in_range = 1;
        }


		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect($url);
		} else {
			return $this->render('create', [
				'model' => $model,
                //'all_range' => $all_range
			]);
		}
	}

	public function actionUpdate($id)
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/address/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
        $all_range = false;
        if(Yii::$app->request->get('range') == 'all_range'){
            $all_range = true;
        }

		$model = new AddressForm($id);
        $model->has_other_zone = false;
        if($all_range){
            $model->in_range = 0;
        }else{
            $model->in_range = 1;
        }

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect($url);
		}
		return $this->render('update', [
			'model' => $model,
		]);
	}
	public function actionDelete($id)
	{
		if (!$url = \Yii::$app->request->get('redirect')) {
			$url = "/address/index";
		}
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['/site/login', 'redirect' => $url]);
		}
		$this->findModel($id)->delete();
		if(Yii::$app->session->get('checkout_address_id')==$id){
			Yii::$app->session->remove('checkout_address_id');
		}
		return $this->redirect($url);
	}

	protected function findModel($id)
	{
		if (($model = Address::findOne(['address_id' => $id, 'customer_id' => \Yii::$app->user->identity->getId()])) !== null) {
			return $model;
		} else {
			return $this->redirect(['index']);
		}
	}
	public function actionGeo(){
		$data=[];
		if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
			$curl=new Curl();
			$url='http://apis.map.qq.com/ws/geocoder/v1/';
			$result=$curl->get($url,['location'=>Yii::$app->request->post('lat').",".Yii::$app->request->post('lng'),'poi_options'=>"address_format=short;radius=500;policy=2",'coord_type'=>5,'key'=>'GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC','get_poi'=>1]);
			if($result->status==0 && $result->result){
				if($pos_data=$result->result->pois){
					$min=null;
					foreach($pos_data as $key=>$pos){
						if($key==0){
							$min=$pos;
							continue;
						}
						if($pos->_distance < $min->_distance){
							$min=$pos;
						}
					}
					$data=[
						'lat'=>$min->location->lat,
						'lng'=>$min->location->lng,
						'address'=>$min->ad_info->district.$min->address,
						'title'=>$min->title,
						'province'=>$min->ad_info->province,
						'city'=>$min->ad_info->city,
						'district'=>$min->ad_info->district,
					];
				}

			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}

	public function actionSuggestion(){
		$data=[];
		if($query=Yii::$app->request->get('query')){
			$curl=new Curl();
			$url='http://apis.map.qq.com/ws/place/v1/suggestion';
			$result=$curl->get($url,['region'=>'青岛','keyword'=>$query,'key'=>'GNWBZ-7FSAR-JL5WY-WIDVS-FHLY2-JVBEC','region_fix'=>1,'policy'=>1]);
			if($result->status==0 && $result->data){
				foreach($result->data as $value){
					$data[]=[
						'value'=>$value->title,
						'label'=>$value->title,
						'address'=>$value->address,
						'lat'=>$value->location->lat,
						'lng'=>$value->location->lng,
						'province'=>$value->province,
						'city'=>$value->city,
						'district'=>$value->district,
					];
				}
			}
		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
	public function actionMyAddress(){
		$data=[];
		if(Yii::$app->request->isAjax && Yii::$app->request->isPost){
		    if(Yii::$app->request->get("in_range") == 1){
                $model=Address::find()->where(['customer_id'=>Yii::$app->user->getId(),'not_in_range'=>0])->orderBy('address_id desc')->all();
            }else{
                $model=Address::find()->where(['customer_id'=>Yii::$app->user->getId()])->orderBy('address_id desc')->all();
            }
			if($model){
				foreach($model as $value){
				    if($value->district->status==1){
                        $data[]=[
                            'address_id'=>$value->address_id,
                            'city'=>$value->citys?$value->citys->name:'',
                            'district'=>$value->district?$value->district->name:"",
                            'address'=>$value->address_1,
                            'username'=>$value->firstname,
                            'telephone'=>$value->telephone,
                            'default'=>$value->default?'cur':'',
                        ];
                    }
				}
			}

		}
		\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $data;
	}
}
