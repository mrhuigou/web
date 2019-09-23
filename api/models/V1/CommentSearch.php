<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class CommentSearch extends OrderDeliveryComment
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['customer_id', 'order_id', 'score', 'created_at', 'send_status'], 'integer'],
			[['comment', 'tags'], 'string'],
		];
	}


	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'customer_id' => 'Customer ID',
			'order_id' => 'Order ID',
			'comment' => 'Comment',
			'score' => 'Score',
			'tags' => 'Tags',
			'created_at' => 'Created At',
			'send_status' => 'Send Status',
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
		$query = OrderDeliveryComment::find();

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		$dataProvider->sort->defaultOrder=['id'=>SORT_DESC];
		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		$query->andFilterWhere(['like', 'comment', $this->comment])
			->andFilterWhere(['=', 'customer_id', $this->customer_id])
			->andFilterWhere(['=', 'score', $this->score])
			->andFilterWhere(['=', 'order_id', $this->order_id])
			->andFilterWhere(['like', 'tags', $this->tags]);
		return $dataProvider;
	}
}
