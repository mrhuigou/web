<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CouponSearch represents the model behind the search form about `api\models\V1\Coupon`.
 */
class CustomerAffiliateSearch extends CustomerAffiliate
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['customer_id', 'status'], 'integer'],
			[['commission'], 'number'],
			[['date_added'], 'safe'],
			[['username', 'telephone'], 'string', 'max' => 255],
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
		$query = CustomerAffiliate::find()->orderBy(['date_added'=>SORT_DESC]);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere([
			'customer_id' => $this->customer_id,
			'commission' => $this->commission,
			'status' => $this->status,
			'date_added' => $this->date_added,
		]);

		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'telephone', $this->telephone]);

		return $dataProvider;
	}
}
