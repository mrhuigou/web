<?php

namespace frontend\modules\club\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\ClubActivity;

/**
 * ActivitySearch represents the model behind the search form about `api\models\V1\Activity`.
 */
class ActivitySearch extends ClubActivity
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id','activity_category_id','fee'], 'integer'],
            [['title', 'signup_end', 'begin_datetime', 'end_datetime'], 'safe'],
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
        $query =ClubActivity::find()->where(['status'=>1]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([ 'end_datetime' => SORT_DESC]),
            'pagination' => [
                'pagesize' => '8',
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->andFilterWhere(['=', 'activity_category_id', $this->activity_category_id])
               ->andFilterWhere(['=', 'fee', $this->fee]);


        return $dataProvider;
    }
}
