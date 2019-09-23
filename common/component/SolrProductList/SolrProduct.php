<?php
namespace common\component\SolrProductList;

use Yii;
use api\models\V1\AttributeDescription;
use api\models\V1\CategoryDisplay;
use api\models\V1\Manufacturer;
use yii\helpers\Url;
class SolrProduct{
    static function getProductList($filter_data = array()){
        if( \Yii::$app->request->get("search")){
            $keyword =  \Yii::$app->request->get("search");
        }else{
            $keyword =  \Yii::$app->request->get("keyword");
        }
        if( \Yii::$app->request->get("lowPrice")){
            $lowPrice =  \Yii::$app->request->get("lowPrice");
        }else{
            $lowPrice =  0;
        }
        if( \Yii::$app->request->get("highPrice")){
            $highPrice =  \Yii::$app->request->get("highPrice");
        }else{
            $highPrice =  10000000;
        }
        if( \Yii::$app->request->get("per-page")){
            $per_page =  \Yii::$app->request->get("per-page");
            if($per_page > 60){
                $per_page = 60;
            }
            if($per_page < 1){
                $per_page = 30;
            }
        }elseif(isset($filter_data['per_page'])){
            $per_page = $filter_data['per_page'];
        }else{
            $per_page =  30;
        }
        if( \Yii::$app->request->get("page")){

            $page =  \Yii::$app->request->get("page");
            if($page < 1){
                $page = 1;
            }
            if($page > 1000000){
                $page = 1;
            }
        }else{
            $page =  1;
        }
        $start = ($page-1)*$per_page;
        $category_id= '';
        $get_category_id = Yii::$app->request->get("category_id");
        if( !empty($get_category_id)){
            $category_id = $get_category_id;
        }
        $get_cat_id = Yii::$app->request->get("cat_id");
        if(!empty($get_cat_id)){
            $category_id = $get_cat_id;
        }
        $parts = explode('-', (string)$category_id);
        $leng = count($parts);
        $cat_id = $parts[$leng-1];
        if(!$keyword && !$cat_id){
            \Yii::$app->getView()->params['breadcrumbs'][] = ['label' => '搜索页', 'url' => ['index'],'template'=> "<li>{link}</li>"];
        }else{
            \Yii::$app->getView()->params['breadcrumbs'][] = ['label' => '搜索页', 'url' => ['index'],'template'=> "<li>{link}></li>"];
        }
        $category_display = CategoryDisplay::findOne($cat_id);
        if(!empty($category_display)){
            $p_category = array();
            $p_category[] = $category_display;

            if($category_display->parent_id != 0){
                $category_display->getParents($category_display,$p_category);
            }

            for($i = count($p_category)-1  ; $i >= 0 ; $i--){
                if($i == 0){
                    \Yii::$app->getView()->params['breadcrumbs'][] = ['label' => $p_category[$i]->description->name, 'url' => ['index','cat_id'=>$p_category[$i]->category_display_id],'template'=> "<li>{link}</li>"];
                }else{
                    \Yii::$app->getView()->params['breadcrumbs'][] = ['label' => $p_category[$i]->description->name, 'url' => ['index','cat_id'=>$p_category[$i]->category_display_id],'template'=> "<li>{link}></li>"];
                }

            }
        }

        if($keyword && !$cat_id){
            \Yii::$app->getView()->params['breadcrumbs'][] = ['label' => "关键字：".$keyword, 'url' => ['index','keyword'=>$keyword],'template'=> "<li>{link}</li>"];
        }

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
            'score'=>Url::to(array_merge(['/category'],array_merge($params,['sort'=>'score']))),
            'record'=>Url::to(array_merge(['/category'],array_merge($params,['sort'=>'record']))),
            'favourite'=>Url::to(array_merge(['/category'],array_merge($params,['sort'=>'favourite']))),
            'review'=>Url::to(array_merge(['/category'],array_merge($params,['sort'=>'review']))),
            'price'=>Url::to(array_merge(['/category'],array_merge($params,['sort'=>'price','order'=>$sort_order]))),
        ];
        if(\Yii::$app->request->get('viewType')=='list'){
            $data['data']['viewType']="list";
            $data['data']['style']['cur']='list';
        }else{
            $data['data']['viewType']='grid';
            $data['data']['style']['cur']='grid';
        }
        $data['data']['style']['grid']=Url::to(array_merge(['/category'],array_merge($params,['viewType'=>'grid'])));
        $data['data']['style']['list']=Url::to(array_merge(['/category'],array_merge($params,['viewType'=>'list'])));


        /************************************************************/


        $query = \Yii::$app->solr->createSelect();
        $helper = $query->getHelper();

        if($keyword=\Yii::$app->request->get('keyword')){
            $query->setQuery('product_name:'.$helper->escapeTerm($keyword));
        }
        if($keyword=\Yii::$app->request->get('search')){
            $query->setQuery('product_name:'.$helper->escapeTerm($keyword));
        }
        if($cat_id){
            $query->createFilterQuery('category')->setQuery('category:'.$helper->escapePhrase($cat_id));
        }
        //$query->createFilterQuery('be_intoinv')->setQuery('status:1');

        //$query->setQuery('status:1');

        if($shop_code){
            $query->createFilterQuery('store')->setQuery('store_code:'.$helper->escapePhrase($shop_code));
        }
        if($store_cate_code=\Yii::$app->request->get('store_cate_code')){
            $query->createFilterQuery('store_cate_code')->setQuery('store_category:'.$helper->escapeTerm($store_cate_code));
        }
        if(Yii::$app->request->get('brand_code')){
            $manufacturer = Manufacturer::find()->where(['code'=>Yii::$app->request->get("brand_code")])->one();
            if($manufacturer){
                $query->createFilterQuery('brand')->setQuery('brand_id:'.$helper->escapePhrase($manufacturer->manufacturer_id));
            }
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
        if(($sort=\Yii::$app->request->get('sort')) &&  in_array(\Yii::$app->request->get('sort'),['record','favourite','price']) ){
            if( Yii::$app->request->get("order")){
                $query->addSort($sort,Yii::$app->request->get("order"));
            }else{
                $query->addSort($sort, $query::SORT_DESC);
            }
            //$query->addSort('sort_order', $query::SORT_DESC);

        }else{
            $query->addSort('score', $query::SORT_DESC);
            $query->addSort('record', $query::SORT_DESC);
        }

        $query->createFilterQuery('DISHES')->setQuery('-product_model:DISHES');
        //$query->createFilterQuery('price')->setQuery('price : ['.$lowPrice .' TO '. $highPrice.']');
        $query->createFilterQuery('DININGTABLE')->setQuery('-product_model:DININGTABLE');
        $query->createFilterQuery('FOODDELIVERY')->setQuery('-product_model:FOODDELIVERY');
        $query->createFilterQuery('DELIVERY')->setQuery('-product_model:DELIVERY');
        $query->createFilterQuery('status')->setQuery('status:1');
        $query->createFilterQuery('be_gift')->setQuery('be_gift:0');
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField('brand')->setField('brand_id')->setMinCount(1);
        $facetSet->createFacetField('attr')->setField('attribute')->setMinCount(1);

        $hl = $query->getHighlighting();
        $hl->setFields('product_name');
        $hl->setSimplePrefix('<span class="H">');
        $hl->setSimplePostfix('</span>');
        $query->setStart($start)->setRows($per_page);
        $resultset =  \Yii::$app->solr->select($query);
        /*
                $dataProvider = new SolrDataProvider([
                    'query' => $query,
                    'modelClass' => 'api\models\V1\ProductBase',
                    'pagination' => [
                        'pagesize' => '16'
                    ]
                ]);
        */

        /*******************************************************************/
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
                        'url'=>Url::to(array_merge(['/category'],$attr_tem_data)),
                    ];
                }
                $brand[]=[
                    'id'=>$value,
                    'name'=>$manufacturer->name,
                    'count'=>$count,
                    'url'=>Url::to(array_merge(['/category'],array_merge($params,['brand_id'=>$value]))),
                ];
            }
        }
        $data['data']['brand']=$brand;
        $facet = $resultset->getFacetSet()->getFacet('attr');
        $attr=[];

        foreach ($facet as $value => $count) {
            list($key,$name)=explode('-',$value);
            $attribute=AttributeDescription::findOne(['attribute_code'=>$key]);
            if(!empty($attribute)){
                $attr_data=\Yii::$app->request->get('attr')?\Yii::$app->request->get('attr'):[];
                if(isset($attr_data[$key])&&$attr_data[$key]==$name){
                    $attr_tem_data=$attr_data;
                    unset($attr_tem_data[$key]);
                    $attr_selected[]=[
                        'name'=>$attribute->name.':'.$name,
                        'url'=>Url::to(array_merge(['/category'],array_merge($params,['attr'=>$attr_tem_data]))),
                    ];
                }
                $attr_data=array_merge($attr_data,[$key=>$name]);
                if(in_array($key,array('BREED','PLACE','VINTAGE'))){
                    $attr['main'][$attribute->name][]=[
                        'name'=>$name,
                        'value'=>$key.'-'.$name,
                        'url'=>Url::to(array_merge(['/category'],array_merge($params,['attr'=>$attr_data]))),
                        'count'=>$count
                    ];
                }else{
                    $attr['sub'][$attribute->name][]=[
                        'name'=>$name,
                        'value'=>$key.'-'.$name,
                        'url'=>Url::to(array_merge(['/category'],array_merge($params,['attr'=>$attr_data]))),
                        'count'=>$count
                    ];
                }
            }


        }
        $data['data']['attr']=isset($attr['main'])?$attr['main']:[];
        $data['data']['attr_sub']=isset($attr['sub'])?$attr['sub']:[];
        $data['data']['attr_selected']=$attr_selected;
        $data['resultset'] = $resultset;


        return $data;
    }
}