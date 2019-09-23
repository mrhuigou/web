<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ReturnBaseSearch represents the model behind the search form about `api\models\V1\ReturnBase`.
 */
class HeatMapSearch extends Model
{
    public $begin_date;
    public $end_date;
    public $low_price;
    public $high_price;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['begin_date','end_date','low_price','high_price'], 'safe'],
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
        //$orders = Order::find()->select('order_id')->where(['sent_to_erp'=>'Y'])->all();

        $subQuery = Order::find()->select(['order_id'])->where(['sent_to_erp'=>'Y']);

        if(!$this->begin_date){
            $this->begin_date = date('Y').'-'.date('m').'-'.'01 00:00:00';
        }
        if(!$this->end_date){
            $this->end_date = date('Y-m-d H:i:s');
        }

        if($this->begin_date){
            $subQuery->andFilterWhere(['>=','date_added',$this->begin_date]);
        }
        if($this->end_date){
            $subQuery->andFilterWhere(['<=','date_added',$this->end_date]);
        }
        if($this->low_price){
            $subQuery->andFilterWhere(['>=','low_price',$this->low_price]);
        }
        if($this->high_price){
            $subQuery->andFilterWhere(['<=','high_price',$this->high_price]);
        }

        $subQuery->groupBy("order_id");

        $dataProvider = new ActiveDataProvider([
            'query' => $subQuery,
        ]);

        return array('dataProvider'=>$dataProvider,'orders'=>$subQuery->all());
    }
    public function attributeLabels(){
        return ['begin_date'=>'开始时间',
            'end_date'=>'结束时间',
            'low_price' =>'最低金额',
            'high_price' => '最高金额'
        ];
    }
}
