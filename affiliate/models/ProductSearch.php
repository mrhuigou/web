<?php

namespace affiliate\models;

use api\models\V1\ProductBase;
use common\component\solr\SolrDataProvider;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OrderSearch represents the model behind the search form about `api\models\V1\Order`.
 */
class ProductSearch extends ProductBase
{
    public $keyword;
    public $beintoinv=1;
    public $product_code;
    public $sort='score';
    public $order=1;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_code','keyword','sort'], 'string','max'=>255],
            [['beintoinv','order'],'integer'],
        ];
    }



    public function attributeLabels()
    {
        return [
            'keyword'=>'商品名称',
            'product_code'=>'商品编码',
            'beintoinv'=>'上架状态',
            'sort'=>'排序',
            'order'=>'升降',
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
        $query = \Yii::$app->solr->createSelect();
        $helper = $query->getHelper();
        if($this->keyword){
            $query->setQuery('product_name:'.$helper->escapePhrase($this->keyword));
        }
        $query->createFilterQuery('DISHES')->setQuery('-product_model:DISHES');
        $query->createFilterQuery('DININGTABLE')->setQuery('-product_model:DININGTABLE');
        $query->createFilterQuery('FOODDELIVERY')->setQuery('-product_model:FOODDELIVERY');
        $query->createFilterQuery('DELIVERY')->setQuery('-product_model:DELIVERY');
        $query->createFilterQuery('status')->setQuery('status:'.$helper->escapePhrase($this->beintoinv));
        if($this->product_code){
            $query->createFilterQuery('product_code')->setQuery('product_code:'.$helper->escapePhrase($this->product_code));
        }
        $query->createFilterQuery('be_gift')->setQuery('be_gift:0');
        if($this->sort){
         $query->addSort($this->sort, $this->order?$query::SORT_DESC:$query::SORT_ASC);
        }else{
            $query->addSort('score', $query::SORT_DESC);
        }
        $dataProvider = new SolrDataProvider([
            'query' => $query,
            'modelClass' => 'api\models\V1\ProductBase',
            'pagination' => [
                'pagesize' => '20'
            ]
        ]);
        return $dataProvider;
    }
}
