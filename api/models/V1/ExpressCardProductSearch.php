<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ExpressCardProduct;

/**
 * ExpressCardProductSearch represents the model behind the search form about `api\models\V1\ExpressCardProduct`.
 */
class ExpressCardProductSearch extends ExpressCardProduct
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'express_card_id', 'quantity', 'status'], 'integer'],
            [['shop_code', 'product_base_code', 'product_code', 'product_name', 'description'], 'safe'],
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
        $query = ExpressCardProduct::find();

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
            'express_card_id' => $this->express_card_id,
            'quantity' => $this->quantity,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'shop_code', $this->shop_code])
            ->andFilterWhere(['like', 'product_base_code', $this->product_base_code])
            ->andFilterWhere(['like', 'product_code', $this->product_code])
            ->andFilterWhere(['like', 'product_name', $this->product_name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
