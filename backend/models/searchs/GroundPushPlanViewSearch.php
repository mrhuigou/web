<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\GroundPushPlanView;

/**
 * GroundPushPlanViewSearch represents the model behind the search form about `api\models\V1\GroundPushPlanView`.
 */
class GroundPushPlanViewSearch extends GroundPushPlanView
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ground_push_plan_id'], 'integer'],
            [['code', 'product_code','status'], 'safe'],

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
        $query = GroundPushPlanView::find();

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
        ]);
        if($this->ground_push_plan_id > 0){
            $query->andFilterWhere(['ground_push_plan_id'=>$this->ground_push_plan_id]);
        }
        if($this->status != 'all'){
            $query->andFilterWhere([ 'status' => $this->status]);
        }

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'product_code', $this->product_code]);

        return $dataProvider;
    }
}
