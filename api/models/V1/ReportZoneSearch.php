<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportZoneSearch extends Order
{
    public $begin_date;
    public $end_date;
    public $address;
    public $order_status_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date','end_date','address','order_status_id'], 'safe'],
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

        $query = Order::find();
        $query->joinWith(['orderShipping']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        if (!$this->validate()) {
            return $dataProvider;
        }
        if($this->begin_date){
            $query->andFilterWhere(['>=','date_added',$this->begin_date]);
        }
        if($this->end_date){
            $query->andFilterWhere(['<=','date_added',date('Y-m-d 23:59:59',strtotime($this->end_date))]);
        }
        if ($this->order_status_id == 'complate') {
            $query->andFilterWhere(['sent_to_erp' =>'Y']);
        } elseif($this->order_status_id) {
            $query->andFilterWhere(['order_status_id' => $this->order_status_id]);
        }
        if($this->address){
            $address=explode("\r\n",$this->address);
            $sub_query=[0=>'or'];
            foreach($address as $key=>$value){
                $sub_query[$key+1]=['like', 'jr_order_shipping.shipping_address_1', $value];
            }
            $query->andFilterWhere($sub_query);
        }


        $query->groupBy('customer_id');

        return $dataProvider;
    }
    public function attributeLabels(){
        return ['begin_date'=>'开始时间',
                'end_date'=>'结束时间',
                'address'=>'区域地址',
                'order_status_id'=>'订单状态'
        ];

    }
}
