<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ReturnBase;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReturnBaseSearch extends ReturnBase
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
            [['return_id', 'order_id', 'customer_id', 'return_status_id', 'send_status', 'is_all_return'], 'integer'],
            [['return_code', 'order_code', 'order_no', 'date_ordered', 'firstname', 'lastname', 'email', 'telephone', 'comment', 'date_added', 'date_modified'], 'safe'],
            [['total'], 'number'],
            [['begin_date','end_date'],'string','max'=>255],
            [['return_status_id'],'integer'],
        ];
    }


    public function attributeLabels()
       {
        return [
        'begin_date'=>'开始时间',
        'end_date'=>'结束时间',
        'order_id'=>'订单 ID',
        'order_no'=>'订单编号',
        'order_status_id'=>'订单状态',
        'firstname'=>'姓名',
        'telephone'=>'电话',
        'return_status_id'=>'退货状态',
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
        $query = ReturnBase::find();

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
            'return_id' => $this->return_id,
            'order_id' => $this->order_id,
            'date_ordered' => $this->date_ordered,
            'customer_id' => $this->customer_id,
            'total' => $this->total,
            'date_added' => $this->date_added,
            'date_modified' => $this->date_modified,
            'send_status' => $this->send_status,
            'is_all_return' => $this->is_all_return,
        ]);
        $query->andFilterWhere(['like', 'return_code', $this->return_code])
            ->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['like', 'firstname', $this->firstname])
            ->andFilterWhere(['like', 'lastname', $this->lastname])
            ->andFilterWhere(['like', 'telephone', $this->telephone])
            ->andFilterWhere(['>=', 'date_added', $this->begin_date])
            ->andFilterWhere(['<=', 'date_added', $this->end_date]);
        if($this->return_status_id>0){
            $query->andFilterWhere(['=', 'return_status_id', $this->return_status_id]);
        }

        $query->orderBy(['date_added'=>SORT_DESC]);
        
        return $dataProvider;
    }
}
