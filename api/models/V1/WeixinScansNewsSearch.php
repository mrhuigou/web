<?php

namespace api\models\V1;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use api\models\V1\WeixinScansNews;

/**
 * WeixinScansNewsSearch represents the model behind the search form about `api\models\V1\WeixinScansNews`.
 */
class WeixinScansNewsSearch extends WeixinScansNews
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'weixin_scans_id', 'sort_order'], 'integer'],
            [['title', 'description', 'pic_url', 'url'], 'safe'],
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
        $query = WeixinScansNews::find()->joinWith(['scan'=>function($query){
	        $query->where('type > 0');
        }]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'jr_weixin_scans_news.id' => $this->id,
            'jr_weixin_scans_news.weixin_scans_id' => $this->weixin_scans_id,
            'jr_weixin_scans_news.sort_order' => $this->sort_order,
        ]);

        $query->andFilterWhere(['like', 'jr_weixin_scans_news.title', $this->title])
            ->andFilterWhere(['like', 'jr_weixin_scans_news.description', $this->description])
            ->andFilterWhere(['like', 'jr_weixin_scans_news.pic_url', $this->pic_url])
            ->andFilterWhere(['like', 'jr_weixin_scans_news.url', $this->url]);
	    $query->orderBy('id desc');
        return $dataProvider;
    }
}
