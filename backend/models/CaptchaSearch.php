<?php
namespace backend\models;
use api\models\V1\VerifyCode;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class CaptchaSearch extends VerifyCode {
	public $phone;
	public $code;
	public $status;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['phone', 'code'], 'string', 'max' => 255],
			[['status'], 'integer'],
		];
	}

	public function attributeLabels()
	{
		return [
			'phone' => '开始时间',
			'code' => '结束时间',
			'status' => '订单 ID'
		];

	}

	/**
	 * @inheritdoc
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
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
		$query = VerifyCode::find();
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		if (!$this->validate()) {
			return $dataProvider;
		}
		$query->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'code', $this->code])
			->andFilterWhere(['=', 'status', $this->status]);
		return $dataProvider;
	}
}
