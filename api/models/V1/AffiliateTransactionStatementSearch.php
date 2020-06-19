<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\AffiliateTransactionFlow;

/**
 * AffiliateTransactionSearch represents the model behind the search form about `api\models\V1\AffiliateTransaction`.
 */
class AffiliateTransactionStatementSearch extends AffiliateTransactionFlow
{
    public $begin_date;
    public $end_date;
    public $order_no;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_no','begin_date','end_date'],'string','max'=>255],
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

     public function attributeLabels()
    {
     return [
     'begin_date'=>'开始时间',
     'end_date'=>'结束时间',
     'order_no'=>'订单编码',
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
        if(!Yii::$app->user->isGuest){
            $query = AffiliateTransactionStatement::find()->where(['affiliate_id'=>Yii::$app->user->getId()]);
        }else{
            $query = AffiliateTransactionStatement::find()->where(['affiliate_id'=>0]);
        }


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        $this->load($params);

        if (!$this->validate()) {

            return $dataProvider;
        }

        if($this->order_no && ($orderModel=Order::findOne(['order_no'=>$this->order_no]))){
            $query->andFilterWhere([
            	'type'=>'order',
                'type_id' =>$orderModel->order_id,
            ]);
        }
        if($this->begin_date){
            $this->begin_date=date('Y-m-d 00:00:00',strtotime($this->begin_date));
	        $query->andFilterWhere(['>=', 'create_at', strtotime($this->begin_date)]);
        }
        if($this->end_date){
            $this->end_date=date('Y-m-d 23:59:59',strtotime($this->end_date));
	        $query->andFilterWhere(['<=', 'create_at', strtotime($this->end_date)]);
        }
        $query->OrderBy(['id'=>SORT_DESC]);

        return $dataProvider;
    }

}
