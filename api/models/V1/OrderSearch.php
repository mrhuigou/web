<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class OrderSearch extends Order
{
    public $begin_date;
    public $end_date;
    public $order_status_id;
    public $begin_price;
    public $end_price;
    public $shipping_username;
    public $shipping_telephone;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no', 'email', 'telephone','shipping_telephone','shipping_username','order_type_code','payment_deal_no','payment_method','total'], 'string','max'=>255],
            [['order_status_id','order_id','customer_id'],'integer'],
            [['begin_date','end_date'],'string','max'=>255],
            [['begin_price','end_price'],'number'],
            [['affiliate_id'],'safe']
        ];
    }



    public function attributeLabels()
    {
        return [
            'begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'order_id'=>'订单 ID',
            'order_no'=>'订单编号',
            'order_type_code' => '订单类型',
            'order_status_id'=>'订单状态',
            'payment_deal_no'=>'交易流水号',
            'firstname'=>'姓名',
            'telephone'=>'电话',
            'begin_price'=>'起始金额',
            'end_price'=>'结束金额',
            'shipping_username'=>'收货人姓名',
            'shipping_telephone'=>'收货电话',
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
        // $query = Order::find()->where(['affiliate_id'=>Yii::$app->user->getId()]);
        $this->load($params);

        $query = Order::find();
        if($this->order_type_code && in_array($this->order_type_code,['normal','presell'])){
            $query->joinWith(['orderShipping']);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'jr_order.order_id', $this->order_id])
            ->andFilterWhere(['like', 'order_no', $this->order_no])
            ->andFilterWhere(['=', 'customer_id', $this->customer_id])
            ->andFilterWhere(['=', 'payment_deal_no', $this->payment_deal_no])
            ->andFilterWhere(['>=', 'date_added', $this->begin_date])
            ->andFilterWhere(['<=', 'date_added', $this->end_date])
            ->andFilterWhere(['>=', 'total', $this->begin_price])
            ->andFilterWhere(['<=', 'total', $this->end_price])
            ->andFilterWhere(["=","affiliate_id",$this->affiliate_id]);
        if($this->order_type_code){
            $query->andFilterWhere(['=', 'order_type_code', $this->order_type_code]);
            if(in_array($this->order_type_code,['normal','presell'])){

                $query->andFilterWhere(['like', 'jr_order_shipping.shipping_firstname', $this->shipping_username])
                    ->andFilterWhere(['=', 'jr_order_shipping.shipping_telephone', $this->shipping_telephone]);
            }
        }

        if($this->order_status_id>0){
            $query->andFilterWhere(['=', 'order_status_id', $this->order_status_id]);
        }

        $query->orderBy(['date_added'=>SORT_DESC]);

        return $dataProvider;
    }
}
