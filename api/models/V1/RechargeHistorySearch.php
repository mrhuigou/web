<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\RechargeHistory;

/**
 * RechargeHistorySearch represents the model behind the search form about `api\models\V1\RechargeHistory`.
 */
class RechargeHistorySearch extends RechargeHistory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'recharge_card_id'], 'integer'],
            [['created_at', 'history_agent', 'recharge_card_info'], 'safe'],
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
        $query = RechargeHistory::find();

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
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'recharge_card_id' => $this->recharge_card_id,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'history_agent', $this->history_agent])
            ->andFilterWhere(['like', 'recharge_card_info', $this->recharge_card_info]);

        return $dataProvider;
    }
}
