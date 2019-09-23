<?php
namespace common\component\SolrProductList;

use api\models\V1\AttributeDescription;
use api\models\V1\Manufacturer;
use common\component\solr\SolrDataProvider;
use yii\helpers\Url;
class SolrProductListH5{
    static function getProductListH5(){
        $params=\Yii::$app->request->get();
        $sort_order="desc";
        $sort_selected='score';
        if(in_array(\Yii::$app->request->get('sort'),['record','favourite','price']) && $sort_selected=\Yii::$app->request->get('sort')){
            if($sort_selected==='price') {
                if(\Yii::$app->request->get('order')=='desc'){
                    $data['data']['sort_order']="asc";
                    $sort_order="asc";
                }else{
                    $data['data']['sort_order']="desc";
                    $sort_order="desc";
                }
            }
        }

        $query = \Yii::$app->solr->createSelect();
        $helper = $query->getHelper();
        if($keyword=\Yii::$app->request->get('keyword')){
            $query->setQuery('product_name:'.$helper->escapeTerm($keyword));
        }
        if($cat_id=\Yii::$app->request->get('cat_id')){
            $query->createFilterQuery('category')->setQuery('category:'.$helper->escapeTerm($cat_id));
        }

        if($shop_code=\Yii::$app->request->get('shop_code')){
            $query->createFilterQuery('store_code')->setQuery('store_code:'.$helper->escapeTerm($shop_code));
        }
        if($store_cate_code=\Yii::$app->request->get('store_cate_code')){
            $query->createFilterQuery('store_category')->setQuery('store_category:'.$helper->escapeTerm($store_cate_code));
        }

        if(\Yii::$app->request->get('brand_id')){
            $query->createFilterQuery('brand')->setQuery('brand_id:'.$helper->escapeTerm(\Yii::$app->request->get('brand_id')));
        }
        $attr_data=\Yii::$app->request->get('attr')?\Yii::$app->request->get('attr'):[];
        if($attr_data){
            foreach($attr_data as $key=>$value){
                $query->createFilterQuery($key)->setQuery('attribute:'.$helper->escapeTerm($key."-".$value));
            }
        }
        if($sort=\Yii::$app->request->get('sort')){
            if($sort=='price' && $sort_order){
                $query->addSort($sort, $sort_order);
            }else{
                $query->addSort($sort, $query::SORT_DESC);
            }
        }else{
            $query->addSort('score', $query::SORT_DESC);
            $query->addSort('record', $query::SORT_DESC);
        }
        $query->createFilterQuery('DISHES')->setQuery('-product_model:DISHES');
        $query->createFilterQuery('DININGTABLE')->setQuery('-product_model:DININGTABLE');
        $query->createFilterQuery('FOODDELIVERY')->setQuery('-product_model:FOODDELIVERY');
        $query->createFilterQuery('DELIVERY')->setQuery('-product_model:DELIVERY');
        $query->createFilterQuery('status')->setQuery('status:1');
        $query->createFilterQuery('be_gift')->setQuery('be_gift:0');
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField('brand')->setField('brand_id')->setMinCount(1);
        $facetSet->createFacetField('attr')->setField('attribute')->setMinCount(1);
        $resultset =  \Yii::$app->solr->select($query);

        $dataProvider = new SolrDataProvider([
            'query' => $query,
            'modelClass' => 'api\models\V1\ProductBase',
            'pagination' => [
                'pagesize' => '10'
            ]
        ]);

        $attr_selected=[];
        $brand=[];
        $facet = $resultset->getFacetSet()->getFacet('brand');
        foreach ($facet as $value => $count) {
            $manufacturer=Manufacturer::findOne(['manufacturer_id'=>$value]);
            if($manufacturer){
                if(isset($params["brand_id"])&&$params["brand_id"]==$value){
                    $attr_tem_data=$params;
                    unset($attr_tem_data["brand_id"]);
                    $attr_selected[]=[
                        'name'=>'品牌:'.$manufacturer->name,
                        'url'=>Url::to(array_merge(['/search'],$attr_tem_data)),
                    ];
                }
                $brand[]=[
                    'id'=>$value,
                    'name'=>$manufacturer->name,
                    'count'=>$count,
                    'url'=>Url::to(array_merge(['/search'],array_merge($params,['brand_id'=>$value]))),
                ];
            }
        }

        $attr=[];
        $facet = $resultset->getFacetSet()->getFacet('attr');
        foreach ($facet as $value => $count) {
            list($key,$name)=explode('-',$value);
            $attribute=AttributeDescription::findOne(['attribute_code'=>$key]);
            $attr_data=\Yii::$app->request->get('attr')?\Yii::$app->request->get('attr'):[];
            if(isset($attr_data[$key])&&$attr_data[$key]==$name){
                $attr_tem_data=$attr_data;
                unset($attr_tem_data[$key]);
                $attr_selected[]=[
                    'name'=>$attribute->name.':'.$name,
                    'url'=>Url::to(array_merge(['/search'],array_merge($params,['attr'=>$attr_tem_data]))),
                ];
            }
            $attr_data=array_merge($attr_data,[$key=>$name]);
            $attr[$attribute->name][]=[
                'name'=>$name,
                'value'=>$key.'-'.$name,
                'url'=>Url::to(array_merge(['/search'],array_merge($params,['attr'=>$attr_data]))),
                'count'=>$count
            ];
        }
        $data['query'] = $query;
        $data['dataProvider'] = $dataProvider;
        //$data['sort_data'] = $sort_data;
        $data['sort_order'] = $sort_order;
        $data['sort_selected'] = $sort_selected;
        $data['brand'] = $brand;
        $data['attr'] = $attr;
        $data['attr_selected'] = $attr_selected;

        return $data;
    }
}