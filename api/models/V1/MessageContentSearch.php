<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\Page;

/**
 * PageSearch represents the model behind the search form about `api\models\V1\Page`.
 */
class MessageContentSearch extends MessageContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_content_id'], 'integer'],
            [['description'], 'string'],
            [['date_added'], 'safe'],
            [['type','device'], 'string', 'max' => 30],
            [[ 'title', 'item_id'], 'string', 'max' => 255],
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
        $query = MessageContent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'message_content_id' => $this->message_content_id,
            'date_added' => $this->date_added,
            'device' => $this->device,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'title', $this->title]);
        $query->orderBy(['date_added'=>SORT_DESC]);

        return $dataProvider;
    }
}
