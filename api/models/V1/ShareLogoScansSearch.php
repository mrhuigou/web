<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ShareLogoScans;

/**
 * ShareLogoScansSearch represents the model behind the search form about `api\models\V1\ShareLogoScans`.
 */
class ShareLogoScansSearch extends ShareLogoScans
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['share_logo_scans_id', 'weixin_scans_id', 'type'], 'integer'],
            [['title','parameter', 'logo_url'], 'safe'],
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
        $query = ShareLogoScans::find();

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
            'share_logo_scans_id' => $this->share_logo_scans_id,
            'weixin_scans_id' => $this->weixin_scans_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'parameter', $this->parameter])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'logo_url', $this->logo_url]);

        return $dataProvider;
    }
}
