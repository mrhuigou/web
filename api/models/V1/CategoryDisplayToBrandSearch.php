<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\CategoryDisplayToBrand;

/**
 * CategoryDisplayToBrandSearch represents the model behind the search form about `api\models\V1\CategoryDisplayToBrand`.
 */
class CategoryDisplayToBrandSearch extends CategoryDisplayToBrand
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_display_id', 'brand_id', 'sort_order'], 'integer'],
            [['image'], 'safe'],
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
        $query = CategoryDisplayToBrand::find();

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
            'category_display_id' => $this->category_display_id,
            'brand_id' => $this->brand_id,
            'sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
