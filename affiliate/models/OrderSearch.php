<?php

namespace affiliate\models;

use api\models\V1\Order;
use api\models\V1\ReturnBase;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class OrderSearch extends Order
{
	public $begin_date;
	public $end_date;
	public $order_no;
	public $commission;
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['order_no'], 'string','max'=>255],
			[['begin_date','end_date'],'string','max'=>255],
            [['commission'], 'string','max'=>255],
		];
	}



	public function attributeLabels()
	{
		return [
			'begin_date'=>'开始时间',
			'end_date'=>'结束时间',
			'order_no'=>'订单编号',
			'commission'=>'佣金收益比例',
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
		if(!Yii::$app->user->isGuest){
			$query = Order::find()->where(['affiliate_id'=>Yii::$app->user->getId()]);
		}else{
			$query = Order::find()->where(['affiliate_id'=>0]);
		}
		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);


		if (!$this->validate()) {
			return $dataProvider;
		}
		$query->andFilterWhere(['like', 'order_no', $this->order_no]);
		if($this->begin_date){
			$query->andFilterWhere(['>=', 'date_added', date('Y-m-d 00:00:00',strtotime($this->begin_date))]);
		}
		if($this->end_date){
			$query->andFilterWhere(['<=', 'date_added', date('Y-m-d 23:59:59',strtotime($this->end_date))]);
		}



		$query->orderBy(['date_added'=>SORT_DESC]);

		return $dataProvider;
	}

	public function searchAllOrder($params){
        $this->load($params);
        $query = Order::find()->where(['<>','order_status_id','7']);
        if($this->begin_date) {
            $query = $query->andWhere(['>=', 'date_added', date('Y-m-d 00:00:00', strtotime($this->begin_date))]);
        }
        if($this->end_date) {
            $query = $query->andWhere(['<=', 'date_added', date('Y-m-d 23:59:59', strtotime($this->end_date))]);
        }

        if(!Yii::$app->user->isGuest){
            $query = $query->andWhere(["affiliate_id"=>Yii::$app->user->getId()]);
        }else{
            $query = $query->andWhere(["affiliate_id"=>0]);
        }
        $query = $query->orderBy(['date_added'=>SORT_DESC])
            ->all();
        return $query;
    }


	public function searchAllReturn($params){
        $this->load($params);
        $query = ReturnBase::find()
            ->joinWith([
                'order'=>function($query){
                }
            ]);
        if($this->begin_date) {
            $query = $query->where(['>=', 'jr_return.date_added', date('Y-m-d 00:00:00', strtotime($this->begin_date))]);
        }
        if($this->end_date) {
            $query = $query->andWhere(['<=', 'jr_return.date_added', date('Y-m-d 23:59:59', strtotime($this->end_date))]);
        }
        if(!Yii::$app->user->isGuest){
            $query = $query->andWhere(["jr_order.affiliate_id"=>Yii::$app->user->getId()]);
        }else{
            $query = $query->andWhere(["jr_order.affiliate_id"=>0]);
        }
        $query = $query->andWhere(["jr_order.affiliate_id"=>Yii::$app->user->getId()]);
        $query = $query ->andWhere(['<>','jr_return.return_status_id','6'])->all();

        return $query;
    }
}
