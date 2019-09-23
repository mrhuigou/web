<?php

namespace backend\models\searchs;

use api\models\V1\CustomerSource;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Customer;

/**
 * CustomerSearch represents the model behind the search form about `api\models\V1\Customer`.
 */
class CustomerAnalysisSearch extends Customer
{
    public $begin_date;
    public $end_date;
    public $group='day'; //days : 按日报表 ；weeks ：按周报表； months：按月报表
    public $subscription ; // 0全部  1 关注  2 未关注
    public $source ; //source=all   GD  DM

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        $this->begin_date = date("Y-m-d").' 00:00:00';
        $this->end_date = date("Y-m-d").' 23:59:59';
        $this->group = 'year';
        $this->source = 'all';
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['begin_date', 'end_date',], 'safe'],
            [['group', 'source','subscription'], 'string'],
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
        $subQuery = Customer::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"']);
//        switch ($this->source){
//            case  'all':
//                $subQuery = Customer::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"']);
//                break;
//            case  'GD':
//                $subQuery = CustomerSource::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"','source_from_type'=>2]);
//                break;
//            case  'DM':
//                $subQuery = CustomerSource::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"','source_from_type'=>3]);
//            default :
//                $subQuery = Customer::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"']);
//                break;
//        }

        $subQuery->groupBy('customer_id');

        $data = $this->getData($subQuery);



        return $data;
    }
    private function getData($subQuery){
        $Query = new \yii\db\Query();
        switch( $this->group) {
//            case 'hour';
//                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m-%d %H') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`","COUNT(tmp.order_id) AS `orders`","SUM(case when tmp.order_type_code='recharge' then 1 else 0 END) as recharge_count","SUM(tmp.total) AS total","SUM(tmp.sale_total) AS sale_total"])->from(['tmp'=>$subQuery]);
//                $Query->groupBy("date");
//                $pagesize=36;
//                break;
            case 'day';
                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m-%d') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
                $Query->groupBy("date");
                $pagesize=date('t');
                break;
            case 'week':
                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y/%u') AS week_day,concat(DATE_FORMAT(tmp.date_added, '%Y/%u'),'---','[',date_format(subdate(tmp.date_added,date_format(tmp.date_added, '%w') - 1),'%Y/%m/%d'),'---',date_format(subdate(tmp.date_added,date_format(tmp.date_added, '%w') - 7),'%Y/%m/%d'),']') date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
                $Query->groupBy("week_day");
                $pagesize=36;
                break;
            case 'month':
                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y-%m') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
                $Query->groupBy("date");
                $pagesize=36;
                break;
            case 'year':
                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
                $Query->groupBy("date");
                $pagesize=36;
                break;
            default :
                $Query->select(["DATE_FORMAT(tmp.date_added, '%Y') AS date","COUNT(DISTINCT tmp.customer_id) AS `customer_count`"])->from(['tmp'=>$subQuery]);
                $Query->groupBy("date");
                $pagesize=36;
        }

        return $Query->all();
    }
    public function attributeLabels(){
        return ['begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'group'=>'分组方式',
            'source'=>'客户来源类型'
        ];

    }
    public function getDM($params){
        $this->load($params);
        $subQuery = CustomerSource::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"'])->andWhere(['source_from_type'=>3]);;
        $data = $this->getData($subQuery);

//        $dataProvider = new ActiveDataProvider([
//            'query' => $data['query']->orderBy("date"),
//            'pagination' => ['pagesize'=>$data['pagesize']]
//        ]);
        return $data;
    }
    public function getGD($params){
        $this->load($params);
        $subQuery = CustomerSource::find()->where(['and','date_added >="'.$this->begin_date.' 00:00:00"' ,'date_added <="'.$this->end_date.' 23:59:59"'])->andWhere(['source_from_type'=>2]);
        $data = $this->getData($subQuery);

//        $dataProvider = new ActiveDataProvider([
//            'query' => $data['query']->orderBy("date"),
//            'pagination' => ['pagesize'=>$data['pagesize']]
//        ]);
        return $data;
    }
}
