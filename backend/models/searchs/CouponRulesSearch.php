<?php

namespace backend\models\searchs;

use api\models\V1\CouponRules;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Lottery;

/**
 * LotterySeach represents the model behind the search form about `api\models\V1\Lottery`.
 */
class CouponRulesSearch extends CouponRules
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_rules_id', 'user_total_limit'], 'integer'],
            [['name', 'start_time', 'end_time'], 'safe'],
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
        $query = CouponRules::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'coupon_rules_id' => $this->coupon_rules_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'user_total_limit' => $this->user_total_limit,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->orderBy('coupon_rules_id desc');
        return $dataProvider;
    }
}
