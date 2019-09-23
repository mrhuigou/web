<?php
namespace backend\models;
use api\models\V1\Order;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class OrderSearch extends Order {
	public $begin_date;
	public $end_date;
	public $begin_price;
	public $end_price;
	public $shipping_username;
	public $shipping_telephone;
	public $affiliate_id;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['order_no', 'email', 'telephone', 'shipping_telephone', 'shipping_username', 'order_type_code', 'payment_deal_no', 'payment_method', 'total'], 'string', 'max' => 255],
			[['order_status_id', 'order_id', 'customer_id'], 'integer'],
			[['begin_date', 'end_date'], 'string', 'max' => 255],
			[['begin_price', 'end_price'], 'number'],
			[['affiliate_id', 'sent_to_erp'], 'safe']
		];
	}

	public function attributeLabels()
	{
		return [
			'begin_date' => '开始时间',
			'end_date' => '结束时间',
			'order_id' => '订单 ID',
			'order_no' => '订单编号',
			'order_type_code' => '订单类型',
			'order_status_id' => '订单状态',
			'payment_deal_no' => '交易流水号',
			'payment_method'=>'支付方式',
			'firstname' => '姓名',
			'telephone' => '电话',
			'begin_price' => '起始金额',
			'end_price' => '结束金额',
			'shipping_username' => '收货人姓名',
			'shipping_telephone' => '收货电话',
			'sent_to_erp' => '同步状态',
            'affiliate_id' => '分销商'
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
		$query->andFilterWhere(['like', 'jr_order.order_id', $this->order_id])
			->andFilterWhere(['like', 'order_no', $this->order_no])
			->andFilterWhere(['=', 'customer_id', $this->customer_id])
			->andFilterWhere(['like', 'telephone', $this->email])
			->andFilterWhere(['like', 'telephone', $this->telephone])
			->andFilterWhere(['=', 'payment_deal_no', $this->payment_deal_no])
			->andFilterWhere(['=', 'payment_method', $this->payment_method])
			->andFilterWhere(['>=', 'date_added', $this->begin_date])
			->andFilterWhere(['>=', 'total', $this->begin_price])
			->andFilterWhere(['<=', 'total', $this->end_price])
			->andFilterWhere(["=", "sent_to_erp", $this->sent_to_erp]);
		if($this->end_date){
			$query->andFilterWhere(['<=', 'date_added', date('Y-m-d 23:59:59',strtotime($this->end_date))]);
		}
        if ($this->affiliate_id) {
            $query->andFilterWhere(['=', 'affiliate_id', $this->affiliate_id]);
        }
		if ($this->order_type_code) {
			$query->andFilterWhere(['=', 'order_type_code', $this->order_type_code]);
		}
		$query->andFilterWhere(['like', 'jr_order_shipping.shipping_firstname', $this->shipping_username])
			->andFilterWhere(['like', 'jr_order_shipping.shipping_telephone', $this->shipping_telephone]);
		if ($this->order_status_id > 0) {
			$query->andFilterWhere(['=', 'order_status_id', $this->order_status_id]);
		}
		if(!Yii::$app->request->get('sort_order')){
			$query->addOrderBy('order_id desc');
		}
		return $dataProvider;
	}
}
