<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ExpressCard;

/**
 * ExpressCardSearch represents the model behind the search form about `api\models\V1\ExpressCard`.
 */
class ExpressCardSearch extends ExpressCard
{
    public $legal_person_name;
//    public $begin_date;
//    public $end_date;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status'], 'integer'],
            [['code', 'name', 'remark','legal_person_name', 'begin_datetime', 'end_datetime'], 'safe'],
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
        $query = ExpressCard::find();
        if($this->legal_person_name){
            $query->joinWith(['legalPerson']);
        }
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);



        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
        ]);
        if($this->legal_person_name){
            $query->andFilterWhere(['like', 'jr_legal_person.name', $this->legal_person_name]);
        }

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['>=', 'begin_datetime', $this->begin_datetime]);

        if($this->end_datetime){
//            $query->andFilterWhere(['<=', 'end_datetime',$this->end_datetime.' 23:59:59']);
            $query->andFilterWhere(['<', 'end_datetime', date('Y-m-d 23:59:59',strtotime($this->end_datetime))]);
        }


        return $dataProvider;
    }
}
