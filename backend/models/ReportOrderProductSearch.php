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
use api\models\V1\OrderProduct;
use api\models\V1\ProductBase;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportOrderProductSearch extends Model {
	public $begin_date;
	public $end_date;
	public $group_value;
	public $order_status_id='complete';
	public function __construct($config = [])
	{
		if (!$this->begin_date) {
			$this->begin_date=date("Y-m-d",time());
		}
		if (!$this->end_date) {
			$this->end_date=date("Y-m-d",time());
		}
		parent::__construct($config);
	}
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['begin_date', 'end_date', 'group_value', 'order_status_id'], 'safe'],
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
		$subQuery = Order::find()->alias('o')->select(['o.order_id']);
		$this->load($params);
		if ($this->begin_date) {
			$subQuery->andFilterWhere(['>=', 'o.date_added', date('Y-m-d 00:00:00', strtotime($this->begin_date))]);
		}
		if ($this->end_date) {
			$subQuery->andFilterWhere(['<=', 'o.date_added', date('Y-m-d 23:59:59', strtotime($this->end_date))]);
		}
		if ($this->order_status_id == 'complete') {
			$subQuery->andFilterWhere(['o.sent_to_erp' => 'Y']);
		} elseif ($this->order_status_id) {
			$subQuery->andFilterWhere(['o.order_status_id' => $this->order_status_id]);
		}
		$Query = new \yii\db\Query();
		$Query->select(['op.product_code', 'op.name','SUM(op.quantity) as quantity_total','SUM(op.total) as product_total','SUM(op.pay_total) as pay_total','count(DISTINCT op.order_id) as order_count','(select quantity-tmp_qty from jr_warehouse_stock where product_code=op.product_code limit 1 ) as stock','(select concat(`name`,"--",`code`) from jr_category_description where category_id=( SELECT category_id from jr_product_base pb WHERE pb.product_base_id = op.product_base_id LIMIT 1 ) limit 1 ) as category_name'])->from(['op'=>OrderProduct::tableName()])->where(['op.order_id'=>$subQuery]);
		if ($this->group_value) {
//			$sub_data = explode("\r\n", $this->group_value);
//			$filter_arr=[];
//			foreach ($sub_data as $value){
//				$filter_arr[]=$value;
//			}
//			$filter_where=['or'];
//			foreach ($filter_arr as $value){
//				array_push($filter_where,['like', 'op.product_base_code', $value]);
//			}
//			$Query->andFilterWhere($filter_where);
//

            $sub_data = explode("\r\n", $this->group_value);
            $filter_arr = [];
            foreach ($sub_data as $value) {
                if ($value && strpos($value,"|")) {
                    list($key, $val) = explode("|", $value);
                    $filter_arr[$key][] = $val;
                }
            }
            $query_filter_where = [];
            $filter_where = [];
            foreach ($filter_arr as $type => $value) {
                switch ($type) {
                    case 'product':
                        $subQuery->andFilterWhere(['in', 'op.product_base_code', $value]);
                        break;
                    case 'category':
                        $cate_query = Category::find()->select('category_id')->where(['or like', 'code', $value]);
                        $sub_query = ProductBase::find()->select('product_base_code')->where(['category_id' => $cate_query]);
                        $Query->andFilterWhere(['in', 'op.product_base_code', $sub_query]);
                        break;
//                    case 'brand':
//                        $cate_query = Manufacturer::find()->select('manufacturer_id')->where(['or like', 'code', $value]);;
//                        $sub_query = ProductBase::find()->select('product_base_code')->where(['manufacturer_id' => $cate_query]);
//                        array_push($filter_where, ['in', 'op.product_base_code', $sub_query]);
//                        break;
                    default :
                        break;
                }
            }

		}
			$Query->groupBy('op.product_code');
			$dataProvider = new ActiveDataProvider([
				'query' => $Query->orderBy("sum(op.quantity) desc"),
			]);
			return $dataProvider;
		}
		public
		function attributeLabels()
		{
			return ['begin_date' => '开始时间',
				'end_date' => '结束时间',
				'group_value' => '商品组',
				'order_status_id' => '订单状态'
			];

		}
	}
