<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class ReportSaleReturnSearch extends Model
{
    public $begin_date;
    public $end_date;
    public $group;
    public $return_status_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date', 'end_date', 'group','return_status_id'], 'safe'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'group'=>'分组',
            'return_status_id'=>'退换状态',
        ];
    }



    public function search($params)
    {
        $this->load($params);
        $subQuery=new \yii\db\Query();
        $subQuery->select(['return_id','date_added','products'=>"(SELECT SUM(rp.quantity)	FROM jr_return_product rp 	WHERE rp.return_id = r.return_id) "])->from(['r'=>ReturnBase::tableName()]);
        if ($this->return_status_id) {
            $subQuery->andFilterWhere(['= ','r.return_status_id',(int)$this->return_status_id]);
        } else {
            $subQuery->andFilterWhere(['> ','r.return_status_id',0]);
        }
        if($this->begin_date){
            $subQuery->andFilterWhere(['>= ','DATE(r.date_added)',$this->begin_date]);
        }
        if($this->end_date){
            $subQuery->andFilterWhere(['<= ','DATE(r.date_added)',$this->end_date]);
        }
        $Query = new \yii\db\Query();
        $Query->select(" MIN(tmp.date_added) AS date_start, MAX(tmp.date_added) AS date_end, COUNT(tmp.return_id) AS `returns`, SUM(tmp.products) AS products ")->from(['tmp'=>$subQuery]);
        switch( $this->group) {
            case 'day';
                $Query->groupBy("DAY(tmp.date_added)");
                break;
            case 'week':
                $Query->groupBy("WEEK(tmp.date_added)");
                break;
            case 'month':
                $Query->groupBy("MONTH(tmp.date_added)");
                break;
            case 'year':
                $Query->groupBy("YEAR(tmp.date_added)");
                break;
            default :
                $Query->groupBy("YEAR(tmp.date_added)");
                break;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $Query,
        ]);
        return $dataProvider;
    }
}
