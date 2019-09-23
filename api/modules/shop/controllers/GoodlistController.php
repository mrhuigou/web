<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 2015/3/23
 * Time: 12:03
 */

namespace api\modules\shop\controllers;
use api\models\V1\AttributeDescription;
use api\models\V1\Manufacturer;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use common\component\image\Image;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class GoodlistController  extends Controller {
    public function actionIndex($data)
    {

        if (!isset($data['display'])) {
            $data['display'] = [];
        }
        if (isset($data['content']) && $data['content']) {
            $content = [];
            foreach ($data['content'] as $value) {
                $content = array_merge($content, $value);
            }
            $data['content'] = $content;
        } else {
            $data['content'] = [];
        }
        if(isset($data['shop_code'])){
            $shop_code=$data['shop_code'];
        }else{
            $shop_code=\Yii::$app->request->get('shop_code');
        }
        $params=\Yii::$app->request->get();
        unset($params['page']);
        unset($params['per-page']);
        $sort_order="desc";
        $data['data']['sort_order']='';
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
            $data['data']['sort_selected']=$sort_selected;
        }else{
            $data['data']['sort_selected']='score';
        }
        $data['data']['sort']=[
            'score'=>Url::to(array_merge(['/shop/category'],array_merge($params,['sort'=>'score']))),
            'record'=>Url::to(array_merge(['/shop/category'],array_merge($params,['sort'=>'record']))),
            'favourite'=>Url::to(array_merge(['/shop/category'],array_merge($params,['sort'=>'favourite']))),
            'review'=>Url::to(array_merge(['/shop/category'],array_merge($params,['sort'=>'review']))),
            'price'=>Url::to(array_merge(['/shop/category'],array_merge($params,['sort'=>'price','order'=>$sort_order]))),
        ];
        if(\Yii::$app->request->get('viewType')=='list'){
            $data['data']['viewType']="list";
            $data['data']['style']['cur']='list';
        }else{
            $data['data']['viewType']='grid';
            $data['data']['style']['cur']='grid';
        }
        $data['data']['style']['grid']=Url::to(array_merge(['/shop/category'],array_merge($params,['viewType'=>'grid'])));
        $data['data']['style']['list']=Url::to(array_merge(['/shop/category'],array_merge($params,['viewType'=>'list'])));



        $query = \Yii::$app->solr->createSelect();
        $helper = $query->getHelper();
        if($keyword=\Yii::$app->request->get('keyword')){
            $query->setQuery('product_name:'.$helper->escapePhrase($keyword));
        }
        // $query->setQuery('store_category:'.\Yii::$app->request->get('cat_id'));
        $hl = $query->getHighlighting();
        $hl->setFields('product_name');
        $hl->setSimplePrefix('<span class="H">');
        $hl->setSimplePostfix('</span>');
        if($shop_code){
            $query->createFilterQuery('store')->setQuery('store_code:'.$helper->escapePhrase($shop_code));
        }
        if(\Yii::$app->request->get('brand_id')){
            $query->createFilterQuery('brand')->setQuery('brand_id:'.$helper->escapePhrase(\Yii::$app->request->get('brand_id')));
        }
        $attr_data=\Yii::$app->request->get('attr')?\Yii::$app->request->get('attr'):[];
        if($attr_data){
            foreach($attr_data as $key=>$value){
                $query->createFilterQuery($key)->setQuery('attribute:'.$helper->escapePhrase($key."-".$value));
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
        }
        $resultset =  \Yii::$app->solr->select($query);
        $pages = new Pagination(['totalCount' =>$resultset->getNumFound(), 'pageSize' => '12','route'=>'/shop/category']);
        $query->setStart($pages->offset)->setRows($pages->limit);
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField('brand')->setField('brand_id')->setMinCount(1);
        $facetSet->createFacetField('attr')->setField('attribute')->setMinCount(1);
        // this executes the query and returns the result
        $resultset =  \Yii::$app->solr->select($query);
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
                        'url'=>Url::to(array_merge(['/shop/category'],$attr_tem_data)),
                    ];
                }
                $brand[]=[
                    'id'=>$value,
                    'name'=>$manufacturer->name,
                    'count'=>$count,
                    'url'=>Url::to(array_merge(['/shop/category'],array_merge($params,['brand_id'=>$value]))),
                ];
            }
        }
        $data['data']['brand']=$brand;
        $facet = $resultset->getFacetSet()->getFacet('attr');
        $attr=[];

        foreach ($facet as $value => $count) {
            list($key,$name)=explode('-',$value);
            $attribute=AttributeDescription::findOne(['attribute_code'=>$key]);
            $attr_data=\Yii::$app->request->get('attr')?\Yii::$app->request->get('attr'):[];
            if(isset($attr_data[$key])&&$attr_data[$key]==$name){
                $attr_tem_data=$attr_data;
                unset($attr_tem_data[$key]);
                $attr_selected[]=[
                    'name'=>$attribute->name.':'.$name,
                    'url'=>Url::to(array_merge(['/shop/category'],array_merge($params,['attr'=>$attr_tem_data]))),
                ];
            }
            $attr_data=array_merge($attr_data,[$key=>$name]);
            if(in_array($key,array('BREED','PLACE','VINTAGE'))){
                $attr['main'][$attribute->name][]=[
                    'name'=>$name,
                    'value'=>$key.'-'.$name,
                    'url'=>Url::to(array_merge(['/shop/category'],array_merge($params,['attr'=>$attr_data]))),
                    'count'=>$count
                ];
            }else{
                $attr['sub'][$attribute->name][]=[
                    'name'=>$name,
                    'value'=>$key.'-'.$name,
                    'url'=>Url::to(array_merge(['/shop/category'],array_merge($params,['attr'=>$attr_data]))),
                    'count'=>$count
                ];
            }

        }
        $data['data']['attr']=isset($attr['main'])?$attr['main']:[];
        $data['data']['attr_sub']=isset($attr['sub'])?$attr['sub']:[];
        $data['data']['attr_selected']=$attr_selected;
        $highlighting = $resultset->getHighlighting();
        $list=[];
        foreach ($resultset as $document) {
            $highlightedDoc = $highlighting->getResult($document->id);
            $product_name=$document->product_name;
            if($highlightedDoc){
                foreach ($highlightedDoc as $field => $highlight) {
                    $product_name= implode('', $highlight);
                }
            }
            $product_base=ProductBase::findOne(['product_base_id'=>$document->id]);
           $list[]=[
               'product_name'=>$product_name,
               'store_code'=>$document->store_code,
               'product_code'=>$document->product_code,
               'price'=>$document->price,
               'image'=>Image::resize($product_base->image,180,180),
               'sub_image'=>$this->getProductMainSku($document->id)
           ];

        }
        $data['data']['products']=$list;
        $data['data']['pages']=  $pages;
        return $data;
    }

    public function getProductMainSku($id){
        $product=Product::find()->where(['product_base_id'=>$id])->all();
        if($product){
            return $product;
        }else{
            return ;
        }


    }



    public function bindActionParams($action, $params){
        return $params;
    }
}
