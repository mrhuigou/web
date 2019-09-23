<?php

namespace backend\models\searchs;

use api\models\V1\CustomerSource;
use api\models\V1\WebLogVisit;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CustomerSearch represents the model behind the search form about `api\models\V1\Customer`.
 */
class WebLogSearch extends WebLogVisit
{
    public $begin_date;
    public $end_date;
    public $group;
    public $url;

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['begin_date', 'end_date',], 'safe'],
            [['group','url'], 'string'],
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

        $subQuery = WebLogVisit::find()->where(['like','url',trim($this->url).'%',false]);
        if($this->begin_date){
            $subQuery->andFilterWhere(['and','time_in/1000 >=UNIX_TIMESTAMP("'.$this->begin_date.'")' ]);
        }
        if($this->end_date){
            $subQuery->andFilterWhere(['and','time_in/1000 <=UNIX_TIMESTAMP("'.$this->end_date.'")' ]);
        }

        if($this->group == 'customer_id'){
            $subQuery->groupBy('customer_id');
        }
        $count = $subQuery->count('id');

        //$data = $this->getData($subQuery);

        return $count;

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

}
