<?php
namespace backend\models;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\base\Model;
/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class ReportDistributionSearch extends Model {
	public $begin_date;
	public $end_date;
	public function __construct($config = [])
	{
		$this->begin_date=date('Y-m-d',strtotime("-1 day"));
		$this->end_date=date('Y-m-d',time());
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['begin_date', 'end_date'], 'required'],
			[['begin_date', 'end_date'], 'string', 'max' => 255],
		];
	}

	public function attributeLabels()
	{
		return [
			'begin_date' => '开始时间',
			'end_date' => '结束时间',
		];
	}


	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$this->load($params);
		if (!$this->validate()) {
			return null;
		}
		$value=['BEGIN_TIME'=>$this->begin_date,'END_TIME'=>$this->end_date];
		$client = new \SoapClient(Yii::$app->params['ERP_SOAP_URL'], ['soap_version' => SOAP_1_1, 'exceptions' => false]);
		$data = $this->CreatRequestParams('syncDistributionQty', [$value]);
		$content = $client->getInterfaceForJson($data);
		$result = $this->getResult($content);
		if($result['status']=='OK'){
			return $result['data'];
		}else{
			return null;
		}
	}
	//生成请求数据方法
	protected function CreatRequestParams($a, $d = [], $v = '1.0')
	{
		$t = time();
		$m = 'webservice';
		$key = 'asdf';
		$data = ['a' => $a, 'c' => 'NONE', 'd' => $d, 'f' => 'json', 'k' => md5($t . $m . $key), 'm' => $m, 'l' => 'CN', 'p' => 'soap', 't' => $t, 'v' => $v];
		return Json::encode($data);
	}
	protected function getResult($data)
	{
		$result = Json::decode($data, true);
		return $result;
	}
}
