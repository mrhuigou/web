<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\RechargeCard;

/**
 * RechargeCardSearch represents the model behind the search form about `api\models\V1\RechargeCard`.
 */
class RechargeCardSearch extends RechargeCard
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'value', 'status'], 'integer'],
            [['card_no','card_code', 'card_pin', 'start_time', 'end_time', 'created_at', 'title'], 'safe'],
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
    public function search($params,$type='')
    {
        $query = RechargeCard::find();

        if($type == 'export'){
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' =>false
            ]);
        }else{
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
        }

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
             'card_no' => $this->card_no,
             'value' => $this->value,
            'card_code' => $this->card_code,
            // 'start_time' => $this->start_time,
            // 'end_time' => $this->end_time,
            'created_at' => $this->created_at,
            
        ]);
        if($this->status && $this->status >=0){
            $query->andFilterWhere(['status' => $this->status]);
        }

        $query->andFilterWhere(['>=', 'start_time', $this->start_time])
            ->andFilterWhere(['<=', 'end_time', $this->end_time])
            // ->andFilterWhere(['like', 'card_code', $this->card_code])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'card_pin', $this->card_pin]);

        $query->orderBy(['created_at'=>SORT_DESC]);

        return $dataProvider;
    }
}
