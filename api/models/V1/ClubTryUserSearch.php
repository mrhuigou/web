<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ClubTryUser;

/**
 * ClubTryUserSearch represents the model behind the search form about `api\models\V1\ClubTryUser`.
 */
class ClubTryUserSearch extends ClubTryUser
{

    public $begin_date;
    public $end_date;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'try_id', 'customer_id', 'zone_id', 'city_id', 'district_id', 'order_id', 'status'], 'integer'],
            [['shipping_name', 'shipping_telephone', 'address', 'postcode', 'creat_at','begin_date','end_date'], 'safe'],
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
    public function search($params,$try_id)
    {
        $query = ClubTryUser::find();

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
            'id' => $this->id,
            'try_id' => $try_id?$try_id:$this->try_id,
            'customer_id' => $this->customer_id,
            'zone_id' => $this->zone_id,
            'city_id' => $this->city_id,
            'district_id' => $this->district_id,
            // 'creat_at' => $this->creat_at,
            'order_id' => $this->order_id,
            // 'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'shipping_name', $this->shipping_name])
            ->andFilterWhere(['>=', 'creat_at', $this->begin_date])
            ->andFilterWhere(['<=', 'creat_at', $this->end_date?$this->end_date." 23:59:59":date("Y-m-d H:i:s")])
            ->andFilterWhere(['like', 'shipping_telephone', $this->shipping_telephone])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'postcode', $this->postcode]);
        if($this->status < 2){
            $query->andFilterWhere(['=', 'status', $this->status]);
        }
        return $dataProvider;
    }
}
