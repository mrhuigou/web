<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CouponSearch represents the model behind the search form about `api\models\V1\Coupon`.
 */
class CouponSearch extends Coupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['coupon_id','expire_seconds', 'shipping', 'quantity','is_entity', 'is_open', 'status'], 'integer'],
            [['name', 'code', 'type', 'date_type', 'date_start', 'date_end', 'user_limit', 'date_added', 'image_url', 'comment'], 'safe'],
            [['discount', 'total'], 'number'],
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
        $query = Coupon::find()->orderBy(['date_added'=>SORT_DESC]);

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
            'coupon_id' => $this->coupon_id,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'date_type'=>$this->date_type,
            'limit_min_quantity'=>$this->limit_min_quantity,
            'total' => $this->total,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'user_limit' => $this->user_limit,
            'is_open' => $this->is_open,
            'is_entity' => $this->is_entity,
            'status' => $this->status,
            'date_added' => $this->date_added,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'code', $this->code])
            ->andFilterWhere(['=', 'type', $this->type])
            ->andFilterWhere(['=', 'user_limit', $this->user_limit])
            ->andFilterWhere(['like', 'comment', $this->comment]);

        return $dataProvider;
    }
}
