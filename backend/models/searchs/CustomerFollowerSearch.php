<?php

namespace backend\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\CustomerFollower;

/**
 * CustomerFollowerSearch represents the model behind the search form about `api\models\V1\CustomerFollower`.
 */
class CustomerFollowerSearch extends CustomerFollower
{
    public $customer_name;
    public $follower_name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'customer_id', 'follower_id'], 'integer'],
            [['status','customer_name','follower_name','creat_at'],'safe']

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
        $this->load($params);
        $query = CustomerFollower::find();
        if($this->customer_name){
            $query->joinWith(['customer']);
        }

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'follower_id' => $this->follower_id,
        ]);

        $query->andFilterWhere(['>=', 'creat_at', strtotime($this->creat_at)]);

        if($this->status != 'all'){
            $query->andFilterWhere([parent::tableName().'.status' => $this->status,]);
        }
        if($this->customer_name){
            $query->andFilterWhere(['like', 'jr_customer.nickname', $this->customer_name]);
        }


        return $dataProvider;
    }
}
