<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Review;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReviewSearch extends Review
{
    public $begin_date;
    public $end_date;
    public $return_status_id;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'rating', 'service', 'delivery', 'status', 'product_id', 'product_base_id', 'store_id', 'order_id'], 'integer'],
            [['text'], 'string'],
            [['date_added', 'date_modified'], 'safe'],
            [['begin_date','end_date'],'string','max'=>255],
            [['author'], 'string', 'max' => 64],
            [['product_code', 'product_base_code', 'store_code'], 'string', 'max' => 32]
        ];
    }


    public function attributeLabels()
    {
        return [
            'begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'review_id' => '评论ID',
            'customer_id' => '用户ID',
            'author' => '用户名',
            'text' => '评论内容',
            'rating' => '商品',
            'service' => '服务',
            'delivery' => '配送',
            'status' => '状态',
            'date_added' => '创建时间',
            'date_modified' => '修改时间',
            'product_id' => '商品包装ID',
            'product_code' => '包装编码',
            'product_base_id' => '商品ID',
            'product_base_code' => '商品编码',
            'store_id' => '店铺ID',
            'store_code' => '店铺编码',
            'order_id' => '订单号',
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
        $query = Review::find();

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
            'review_id' => $this->review_id,
            'order_id' => $this->order_id,
        ]);

          $query->andFilterWhere(['like', 'text', $this->text])
              ->andFilterWhere(['like', 'author', $this->author])
              ->andFilterWhere(['like', 'rating', $this->rating])
              ->andFilterWhere(['like', 'service', $this->service])
              ->andFilterWhere(['like', 'delivery', $this->delivery])
              ->andFilterWhere(['like', 'status', $this->status])
              ->andFilterWhere(['>=', 'date_added', $this->begin_date])
              ->andFilterWhere(['<=', 'date_added', $this->end_date]);

        $query->orderBy(['date_added'=>SORT_DESC]);

        return $dataProvider;
    }
}
