<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ClubActivityUserSearch represents the model behind the search form about `api\models\V1\ClubActivityUser`.
 */
class ClubActivityUserSearch extends OrderActivity
{

    public $begin_date;
    public $end_date;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date','end_date'], 'safe'],
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
    public function search($params,$activity_id)
    {
        $query = OrderActivity::find();

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
            'activity_id' => $activity_id?$activity_id:$this->activity_id
        ]);
        return $dataProvider;
    }
}
