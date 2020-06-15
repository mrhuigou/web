<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\GroundPushPlan;

/**
 * GroundPushPlanSearch represents the model behind the search form about `api\models\V1\GroundPushPlan`.
 */
class GroundPushPlanSearch extends GroundPushPlan
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'create_at', 'ground_push_point_id'], 'integer'],
            [['code', 'name', 'begin_date_time', 'end_date_time', 'shipping_end_time', 'contact_name', 'contact_tel'], 'safe'],
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
        $query = GroundPushPlan::find();

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
            'id' => $this->id,
            'begin_date_time' => $this->begin_date_time,
            'end_date_time' => $this->end_date_time,
            'shipping_end_time' => $this->shipping_end_time,
            'status' => $this->status,
            'create_at' => $this->create_at,
            'ground_push_point_id' => $this->ground_push_point_id,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'contact_name', $this->contact_name])
            ->andFilterWhere(['like', 'contact_tel', $this->contact_tel]);

        $query->orderBy(['id'=>SORT_DESC]);
        return $dataProvider;
    }
}
