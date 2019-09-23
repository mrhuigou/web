<?php

namespace affiliate\models;

use api\models\V1\Customer;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class CustomerSearch extends Customer
{
	public $begin_date;
	public $end_date;
	public function __construct(array $config = [])
    {
        $this->begin_date=date('Y-m-d',time());
        parent::__construct($config);
    }

    /**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['begin_date','end_date'],'string','max'=>255],
		];
	}



	public function attributeLabels()
	{
		return [
			'begin_date'=>'开始时间',
			'end_date'=>'结束时间',
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
		$query = Customer::find()->where(['affiliate_id'=>Yii::$app->user->getId()]);
		$this->load($params);

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);


		if (!$this->validate()) {
			return $dataProvider;
		}
		if($this->begin_date){
			$query->andFilterWhere(['>=', 'date_added', date('Y-m-d 00:00:00',strtotime($this->begin_date))]);
		}
		if($this->end_date){
			$query->andFilterWhere(['<=', 'date_added', date('Y-m-d 23:59:59',strtotime($this->end_date))]);
		}


		$query->orderBy(['customer_id'=>SORT_DESC]);

		return $dataProvider;
	}
}
