<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2017/1/14
 * Time: 11:27
 */
namespace backend\models;
use api\models\V1\Category;
use api\models\V1\Manufacturer;
use api\models\V1\Order;
use api\models\V1\ProductBase;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportOrderCustomerSearch extends Model {
	public $begin_date;
	public $end_date;
	public $group = 'product';
	public $group_value;
	public $order_status_id = "complete";

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['begin_date', 'end_date','group', 'group_value', 'order_status_id'], 'safe'],
		];
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
			$subQuery = Order::find()->alias('o')->joinWith('orderProducts op');
			$this->load($params);
			if ($this->begin_date) {
				$subQuery->andFilterWhere(['>=', 'o.date_added', $this->begin_date]);
			}
			if ($this->end_date) {
				$subQuery->andFilterWhere(['<=', 'o.date_added', date('Y-m-d 23:59:59', strtotime($this->end_date))]);
			}
			if ($this->order_status_id == 'complete') {
				$subQuery->andFilterWhere(['o.sent_to_erp' => 'Y']);
			} elseif ($this->order_status_id) {
				$subQuery->andFilterWhere(['o.order_status_id' => $this->order_status_id]);
			}
			if ($this->group == 'data' && $this->group_value) {
				$sub_data = explode("\r\n", $this->group_value);
				$filter_arr = [];
				foreach ($sub_data as $value) {
					if ($value && strpos($value,"|")) {
						list($key, $val) = explode("|", $value);
						$filter_arr[$key][] = $val;
					}
				}
				$filter_where = ['or'];
				foreach ($filter_arr as $type => $value) {
					switch ($type) {
						case 'product':
							array_push($filter_where, ['in', 'op.product_base_code', $value]);
							break;
						case 'category':
							$cate_query = Category::find()->select('category_id')->where(['or like', 'code', $value]);;
							$sub_query = ProductBase::find()->select('product_base_code')->where(['category_id' => $cate_query]);
							array_push($filter_where, ['in', 'op.product_base_code', $sub_query]);
							break;
						case 'brand':
							$cate_query = Manufacturer::find()->select('manufacturer_id')->where(['or like', 'code', $value]);;
							$sub_query = ProductBase::find()->select('product_base_code')->where(['manufacturer_id' => $cate_query]);
							array_push($filter_where, ['in', 'op.product_base_code', $sub_query]);
							break;
						default :
							break;
					}
				}
				$subQuery->andFilterWhere($filter_where);
			}
			$Query = new \yii\db\Query();
			$Query->select(['tmp.customer_id', 'tmp.telephone', "count(DISTINCT tmp.order_id) as order_count", "MAX(tmp.date_added) as last_date"])->from(['tmp' => $subQuery])->groupBy("tmp.customer_id");
			$dataProvider = new ActiveDataProvider([
				'query' => $Query->orderBy("tmp.customer_id asc"),
			]);
			return $dataProvider;


	}

	public function attributeLabels()
	{
		return ['begin_date' => '开始时间',
			'end_date' => '结束时间',
			'group' => '类型',
			'group_value' => '类型值',
			'order_status_id' => '订单状态'
		];

	}
}
