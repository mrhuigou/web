<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportOrderSearch extends Order
{
    public $begin_date;
    public $end_date;
    public $group='day';
    public $order_status_id='complete';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date','end_date','group','order_status_id','order_type_code'], 'safe'],
        ];
    }

    public function __construct(array $config = [])
    {
        $this->end_date =  date("Y-m-d");
        $this->begin_date = date("Y-m-01");

        parent::__construct($config);
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

        $subQuery = Order::find()->alias('o')->select(['o.order_id','o.customer_id','o.order_type_code','o.date_added','o.total','SUM(op.total) as sale_total,(SELECT min(jro.date_added) FROM jr_order jro WHERE jro.customer_id = o.customer_id and jro.sent_to_erp="Y") as first_order_date,(SELECT jrc.date_added FROM jr_customer jrc WHERE jrc.customer_id = o.customer_id ) as sign_date'])->joinWith('orderProducts op');
        $this->load($params);
        if($this->order_type_code){
            $subQuery->andFilterWhere(['order_type_code' => $this->order_type_code]);
        }
        if ($this->order_status_id == 'complete') {
            $subQuery->andFilterWhere(['sent_to_erp' => 'Y']);
        } elseif($this->order_status_id) {
            $subQuery->andFilterWhere(['order_status_id' => $this->order_status_id]);
        }
        if($this->begin_date){
            $subQuery->andFilterWhere(['>=','date_added',$this->begin_date]);
        }
        if($this->end_date){
            $subQuery->andFilterWhere(['<=','date_added',date('Y-m-d 23:59:59',strtotime($this->end_date))]);
        }
	    $subQuery->groupBy('o.order_id');
        $Query = new \yii\db\Query();


        switch( $this->group) {
	        case 'hour';
		        $Query->select([
		                "DATE_FORMAT(tmp.date_added, '%Y-%m-%d %H') AS date",
                        "COUNT(DISTINCT tmp.customer_id) AS `customer_count`",
                    "COUNT(tmp.order_id) AS `orders`",
                    "SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count",
                    "SUM(tmp.total) AS total",
                    "SUM(tmp.sale_total) AS sale_total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m-%d %H') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d %H') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m-%d %H') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d %H') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y-%m-%d %H') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d %H')  THEN 1 ELSE 0 END) AS sign_date_count"
                ])->from(['tmp'=>$subQuery]);
		        $Query->groupBy("date");
		        $pagesize=36;
		        break;
            case 'day';
	            $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m-%d') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m-%d') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m-%d') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y-%m-%d') = DATE_FORMAT(tmp.date_added, '%Y-%m-%d')  THEN 1 ELSE 0 END) AS sign_date_count"

                ])->from(['tmp'=>$subQuery]);
                $Query->groupBy("date");
	            $pagesize=date('t');
                break;
            case 'week':
	            $Query->select(["DATE_FORMAT(tmp.date_added, '%Y/%u') AS week_day,concat(DATE_FORMAT(tmp.date_added, '%Y/%u'),'---','[',date_format(subdate(tmp.date_added,date_format(tmp.date_added, '%w') - 1),'%Y/%m/%d'),'---',date_format(subdate(tmp.date_added,date_format(tmp.date_added, '%w') - 7),'%Y/%m/%d'),']') date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y/%u') = DATE_FORMAT(tmp.date_added, '%Y/%u') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y/%u') = DATE_FORMAT(tmp.date_added, '%Y/%u') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y/%u') = DATE_FORMAT(tmp.date_added, '%Y/%u') THEN 1 ELSE 0 END) AS sign_date_count"
                    ])->from(['tmp'=>$subQuery]);
                $Query->groupBy("week_day");
	            $pagesize=36;
                break;
            case 'month':
	            $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m') = DATE_FORMAT(tmp.date_added, '%Y-%m') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y-%m') = DATE_FORMAT(tmp.date_added, '%Y-%m') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y-%m') = DATE_FORMAT(tmp.date_added, '%Y-%m') THEN 1 ELSE 0 END) AS sign_date_count"
                    ])->from(['tmp'=>$subQuery]);
	            $Query->groupBy("date");
	            $pagesize=36;
                break;
            case 'year':
	            $Query->select(["DATE_FORMAT(tmp.date_added, '%Y') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count ",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') THEN 1 ELSE 0 END) AS sign_date_count "
                ])->from(['tmp'=>$subQuery]);
	            $Query->groupBy("date");
	            $pagesize=36;
                break;
            default :
	            $Query->select(["DATE_FORMAT(tmp.date_added, '%Y') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) THEN 1 ELSE 0 END) AS firt_order_count",
                    "SUM( CASE WHEN date_format(tmp.first_order_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') and UNIX_TIMESTAMP(tmp.first_order_date) = UNIX_TIMESTAMP(tmp.date_added) and date_format(tmp.sign_date,'%Y') = DATE_FORMAT(tmp.date_added, '%Y') THEN 1 ELSE 0 END) AS sign_date_count"
                ])->from(['tmp'=>$subQuery]);
	            $Query->groupBy("date");
	            $pagesize=36;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $Query->orderBy("date desc"),
	        'pagination' => ['pagesize'=>$pagesize]
        ]);
        return $dataProvider;
    }
    public function attributeLabels(){
        return ['begin_date'=>'开始时间',
                'end_date'=>'结束时间',
                'group'=>'分组',
                'order_status_id'=>'订单状态'
        ];

    }
}
