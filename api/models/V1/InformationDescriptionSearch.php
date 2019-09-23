<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\InformationDescription;

/**
 * InformationDescriptionSearch represents the model behind the search form about `api\models\V1\InformationDescription`.
 */
class InformationDescriptionSearch extends InformationDescription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['information_id', 'language_id', 'type'], 'integer'],
            [['title', 'description', 'author', 'brand', 'image', 'date_added', 'date_modified', 'meta_keyword', 'meta_description'], 'safe'],
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
        $query = InformationDescription::find();

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
            'information_id' => $this->information_id,
            'language_id' => $this->language_id,
            'type' => $this->type,
            'date_added' => $this->date_added,
            'date_modified' => $this->date_modified,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'brand', $this->brand])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'meta_keyword', $this->meta_keyword])
            ->andFilterWhere(['like', 'meta_description', $this->meta_description]);

        $query->orderBy(['date_added'=>SORT_DESC]);
        
        return $dataProvider;
    }
}
