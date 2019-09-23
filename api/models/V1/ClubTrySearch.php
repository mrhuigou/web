<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ClubTry;
use api\models\V1\Product;

/**
 * ClubTrySearch represents the model behind the search form about `api\models\V1\ClubTry`.
 */
class ClubTrySearch extends ClubTry
{
    public $product_code;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'product_base_id', 'product_id', 'quantity', 'limit_user', 'click_count', 'like_count', 'comment_count', 'share_count', 'sort_order', 'status'], 'integer'],
            [['title', 'description', 'image', 'begin_datetime', 'end_datetime', 'creat_at', 'update_at', 'product_code'], 'safe'],
            [['price'], 'number'],
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
        $query = ClubTry::find();

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
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['>=', 'begin_datetime', $this->begin_datetime])
            ->andFilterWhere(['<=', 'end_datetime', $this->end_datetime]);

        if (empty($this->product_code)) {
            $product = Product::find()->where(['product_code'=>$this->product_code])->one();
            if ($product) {
                $query->andFilterWhere(['==', 'product_id', $product->product_id]);
            }
        }
        $query->orderBy(['creat_at'=>SORT_DESC]);
        return $dataProvider;
    }
}
