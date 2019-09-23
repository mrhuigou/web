<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\GroundPushStock;

/**
 * GroundPushStockSearch represents the model behind the search form about `api\models\V1\GroundPushStock`.
 */
class GroundPushStockSearch extends GroundPushStock
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ground_push_point_id', 'qty', 'tmp_qty', 'version'], 'integer'],
            [['product_code', 'last_time'], 'safe'],
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
        $query = GroundPushStock::find();

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

//            'qty' => $this->qty,
//            'tmp_qty' => $this->tmp_qty,
//            'last_time' => $this->last_time,
//            'version' => $this->version,
        ]);
        if($this->ground_push_point_id){
            $query->andFilterWhere(['ground_push_point_id' => $this->ground_push_point_id]);
        }


        $query->andFilterWhere(['like', 'product_code', $this->product_code]);

        return $dataProvider;
    }
}
