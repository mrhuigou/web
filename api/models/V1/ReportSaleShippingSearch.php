<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportSaleShippingSearch extends Model
{
    public $begin_date;
    public $end_date;
    public $group;
    public $order_status_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            [['begin_date', 'end_date', 'group', 'order_status_id'], 'safe']];
    }


    public function attributeLabels()
    {
        return [
            'begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'group'=>'分组',
            'order_status_id'=>'订单状态',
        ];
    }

    public function search($params)
    {
        $this->load($params);
        $Query=new \yii\db\Query();
        $Query->select("MIN(o.date_added) AS date_start, MAX(o.date_added) AS date_end, ot.title, SUM(ot.value) AS total, COUNT(o.order_id) AS `orders` ")->from(['ot'=>OrderTotal::tableName()])->leftJoin(['o'=>Order::tableName()],'ot.order_id = o.order_id');

        $Query->andFilterWhere(['=','ot.code','shipping']);

        if ($this->order_status_id == 'complete') {
            $Query->andFilterWhere(['o.order_status_id'=>['11','5','10','2','3','9']]);
        } else {
            $Query->andFilterWhere(['>','o.order_status_id',0]);
        }
        if($this->begin_date){
            $Query->andFilterWhere(['>=','DATE(o.date_added)',$this->begin_date]);
        }
        if($this->end_date){
            $Query->andFilterWhere(['<=','DATE(o.date_added)',$this->end_date]);
        }


        switch( $this->group) {
            case 'day';
                $Query->groupBy("DAY(o.date_added),ot.title");
                break;
            case 'week':
                $Query->groupBy("WEEK(o.date_added), ot.title");
                break;
            case 'month':
                $Query->groupBy(" MONTH(o.date_added), ot.title");
                break;
            case 'year':
                $Query->groupBy(" YEAR(o.date_added), ot.title");
                break;
            default :
                $Query->groupBy(" YEAR(o.date_added), ot.title");
                break;
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $Query,
        ]);

        return $dataProvider;
    }
}
