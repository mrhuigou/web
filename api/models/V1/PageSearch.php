<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Page;

/**
 * PageSearch represents the model behind the search form about `api\models\V1\Page`.
 */
class PageSearch extends Page
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'sort_order', 'status',], 'integer'],
            [['type', 'date_added', 'image'], 'safe'],
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
        $query = Page::find();

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
            'page_id' => $this->page_id,
            'sort_order' => $this->sort_order,
            'status' => $this->status,
            'date_added' => $this->date_added,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type]);

        $query->orderBy(['date_added'=>SORT_DESC]);
        
        return $dataProvider;
    }
}
