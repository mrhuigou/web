<?php
namespace frontend\controllers;
use common\component\Curl\Curl;
use common\models\User;
use Yii;
use api\models\V1\Address;
use frontend\models\AddressForm;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AddressController extends \yii\web\Controller {
	public $layout = 'main-user';

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
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$address = Address::find()->where(['customer_id' => \Yii::$app->user->getId()])->orderBy('address_id')->all();
		return $this->render('index', ['address' => $address]);
	}

	public function actionCreate()
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = new AddressForm();
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}
		return $this->render('create', [
			'model' => $model
		]);

	}

	public function actionUpdate($id)
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = new AddressForm($id);
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['index']);
		}
		return $this->render('update', [
			'model' => $model
		]);

	}

	public function actionSetdefault($id)
	{
		if (\Yii::$app->user->isGuest) {
			return $this->redirect('/site/login');
		}
		$model = $this->findModel($id);
		if ($model->customer_id) {
			$customer = User::findIdentity(Yii::$app->user->getId());
			$customer->address_id = $id;
			$customer->save();
			return $this->redirect(['index']);
		} else {
			return $this->redirect(['index']);
		}
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
		return $this->redirect($url);
	}

	protected function findModel($id)
	{
		if (($model = Address::findOne(['address_id' => $id, 'customer_id' => \Yii::$app->user->getId()])) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
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
						'value'=>str_replace($value->province.$value->city,'',$value->address).$value->title,
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
}
