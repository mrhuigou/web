<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ExpressCardView;

/**
 * ExpressCardViewSearch represents the model behind the search form about `api\models\V1\ExpressCardView`.
 */
class ExpressCardViewSearch extends ExpressCardView
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'express_card_id', 'status', 'version'], 'integer'],
            [['card_no', 'card_pwd'], 'safe'],
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
        $query = ExpressCardView::find();

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
//            'status' => $this->status,
//            'version' => $this->version,
        ]);


        $query->andFilterWhere(['like', 'card_no', $this->card_no]);
        if($this->status == 0 || $this->status ==1){
            $query->andFilterWhere(['status'=>$this->status]);
        }

//            ->andFilterWhere(['like', 'card_pwd', $this->card_pwd]);

        return $dataProvider;
    }
}
