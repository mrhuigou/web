<?php
namespace h5\controllers;
use api\models\V1\AttributeDescription;
use api\models\V1\CategoryDisplay;
use api\models\V1\Manufacturer;
use api\models\V1\Product;
use api\models\V1\ProductBase;
use common\component\OpenSearch\CloudsearchClient;
use common\component\OpenSearch\CloudsearchSearch;
use common\component\OpenSearch\CloudsearchSuggest;
use common\models\search\ItemSearch;
use yii\base\Exception;
use yii\data\Pagination;
use yii\elasticsearch\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

class SearchController extends \yii\web\Controller {
	public function actionIndex()
	{
		$params = \Yii::$app->request->getQueryParams();
//print_r($params);
		if (in_array(\Yii::$app->request->get('view'), ['grid', 'list'])) {
			if (\Yii::$app->request->get('view') == 'grid') {
				$url_params = array_merge(['/search/index'], array_merge($params, ['view' => 'list']));
			} else {
				$url_params = array_merge(['/search/index'], array_merge($params, ['view' => 'grid']));
			}
		} else {
			$url_params = array_merge(['/search/index'], array_merge($params, ['view' => 'list']));
		}
		$view = Url::to($url_params, true);
		if (in_array(\Yii::$app->request->get('sort'), ['record', 'favourite', 'price'])) {
			if (\Yii::$app->request->get('order') && \Yii::$app->request->get('order') == 'desc') {
				$sort_order = "asc";
			} else {
				$sort_order = "desc";
			}
			$sort_selected = \Yii::$app->request->get('sort');
		} else {
			if(!\Yii::$app->request->get('keyword') && !\Yii::$app->request->get('sort')){
				$sort_selected = 'record';
				$sort_order = 'desc';
			}else{
				$sort_selected = 'score';
				$sort_order = 'desc';
			}
		}
		$sort_data = [
			'score' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'score'])), true),
			'record' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'record'])), true),
			'favourite' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'favourite'])), true),
			'review' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'review'])), true),
			'price' => Url::to(array_merge(['/search/index'], array_merge($params, ['sort' => 'price', 'order' => $sort_order])), true),
		];
		$access_key = \Yii::$app->params['open_search']['access_key'];
		$secret = \Yii::$app->params['open_search']['secret'];
		$host = "http://opensearch-cn-qingdao.aliyuncs.com";//根据自己的应用区域选择API
		$key_type = "aliyun";  //固定值，不必修改
		$opts = ['host' => $host];
		$client = new CloudsearchClient($access_key, $secret, $opts, $key_type);
		// 实例化一个搜索类
		$search_obj = new CloudsearchSearch($client);
		// 指定一个应用用于搜索
		$search_obj->addIndex("jiarun_item_db");
		// 指定搜索关键词
		if ($keyword = addslashes (strip_tags(\Yii::$app->request->get('keyword')))) {
			$search_obj->addQuery("product_name:'".Html::encode($keyword)."'");
		}
		// 指定搜索店铺商品
		if ($shop_code = \Yii::$app->request->get('shop_code')) {
			$search_obj->addQuery("store_code:'".$shop_code."'");
		}
		// 指定搜索品牌商品
		if ($brand_code = \Yii::$app->request->get('brand_code')) {
			$search_obj->addQuery("brand_code:'".$brand_code."'");
		}
		$search_obj->addQuery("status:'1'");

		if ($cat_id = \Yii::$app->request->get('cat_id')) {
			$search_obj->addFilter("category=".intval($cat_id));
		}


		if ($store_cate_code = \Yii::$app->request->get('store_cate_code')) {
			$search_obj->addFilter("store_category=\"".$store_cate_code."\"");
		}
		if ($brand_id = \Yii::$app->request->get('brand_id')) {
			$search_obj->addFilter("brand_id=".intval($brand_id));
		}

		$attr_data = \Yii::$app->request->get('attr') ? \Yii::$app->request->get('attr') : [];
		if ($attr_data) {
			foreach ($attr_data as $key => $value) {
				if ($value !== "") {
					$search_obj->addFilter("attribute=\"".$key . "-" . $value."\"");
				}
			}
		}
		$search_obj->addFilter("be_gift=0");
		$search_obj->addFilter('(product_model="NORMAL" OR product_model="FRESH" OR product_model="COMBO") ');
        $search_obj->addSort("stock_count","-");
		if ($sort_selected == 'record') {
			$search_obj->addSort("record","-");
		}
		if ($sort_selected == 'favourite') {
			$search_obj->addSort("favourite","-");
		}
		if ($sort_selected == 'review') {
			$search_obj->addSort("review","-");
		}
		if ($sort_selected == 'price') {
			if($sort_order=="desc"){
				$search_obj->addSort("price","-");
			}else{
				$search_obj->addSort("price","+");
			}
		}
        $search_obj->addSort("id","+");
		// 指定返回的搜索结果的格式为json
		$page=\Yii::$app->request->post('page')?\Yii::$app->request->post('page'):1;

		//print_r(\Yii::$app->request->getQueryParams());exit;
		$search_obj->setFormat("json");
		$search_obj->setStartHit(($page-1)*10);
		$search_obj->setHits(10);
		$search_obj->addRerankSize(1000);
		$search_obj->addAggregate('brand_code','count()','','20');
		$search_obj->addAggregate('attribute','count()','','20');
		//获取搜索结果。
		$search_result = json_decode($search_obj->search(),true);
		$result = $search_result["result"];
		$page_total=ceil($result['total']/10);
		$models=[];
        $models1 = [];
        $models2 = [];
		if($result['items'] && $result['items']){
			foreach ($result['items'] as $value){
                $product = Product::findOne(['product_code'=>$value['product_code']]);
                if($product){
                    $productbase = $product->productBase;//ProductBase::findOne(['product_base_id'=>$value['id']]);
                    if($productbase){
                        $models[]=[
                            'product_name'=>$value['product_name'],
                            'productBase'=>ProductBase::findOne(['product_base_id'=>$value['id']])
                        ];
//                        if($productbase->stockCount > 0){
//                            //该models1数组变量不能指定简直，否则影响 array_merage排序
//                            $models1[]=[
//                                'product_name'=>$value['product_name'],
//                                'productBase'=>ProductBase::findOne(['product_base_id'=>$value['id']])
//                            ];
//                        }
//                        if($productbase->stockCount <= 0){
//                            $models2[]=[
//                                'product_name'=>$value['product_name'],
//                                'productBase'=>ProductBase::findOne(['product_base_id'=>$value['id']])
//                            ];
//                        }
                    }
                }


			}
		}
       // $models = array_merge($models1,$models2);
		$filter_attr = [];
		if($result['facet'] && $facts=$result['facet']){
			foreach ($facts as $fact){
				if($fact['key']=='brand_code' && $fact['items']){
					foreach ($fact['items'] as $item){
						if (!$brand = Manufacturer::findOne(['code' => $item['value']])) {
							continue;
						}
						$attr_selected=false;
						if (isset($params["brand_code"]) && $params["brand_code"] == $brand->code) {
							$attr_selected=true;
						}
						$filter_attr['brand'][] = [
							'code' => 'brand_code',
							'value'=>$brand->code,
							'name' => $brand->name,
							'count' => $item['count'],
							'selected'=>$attr_selected
						];
					}
				}
				if($fact['key']=='attribute' && $fact['items']){
					foreach ($fact['items'] as $item){
						$pos = strpos($item['value'], "-");
						$key = substr($item['value'], 0, $pos);
						$name = substr($item['value'], $pos + 1, strlen($item['value']));
						if (!$attribute = AttributeDescription::findOne(['attribute_code' => $key])) {
							continue;
						}
						$attr_selected=false;
						if (isset($params["attr"]) && array_key_exists($key,$params["attr"])) {
							$attr_selected=true;
						}
						$filter_attr[$attribute->name][] = [
							'code' => 'attr['.$key.']',
							'value'=>$name,
							'name' => $name,
							'count' => $item['count'],
							'selected'=>$attr_selected
						];
					}
				}
			}
		}
		$category=CategoryDisplay::findOne($cat_id);
		if($category){
			if($filter_category=$category->getChild()){
				foreach ($filter_category as $item){
					if (isset($params["cat_id"]) && $item->category_display_id==$params["cat_id"]) {
						$attr_selected=true;
					}else{
						$attr_selected=false;
					}
					$filter_attr['category'][] = [
						'code' => 'cat_id',
						'value'=>$item->category_display_id,
						'name' => $item->description->name,
						'count' => 0,
						'selected'=>$attr_selected
					];
				}

			}
		}
		if(\Yii::$app->request->isAjax){
			return $this->renderAjax('index-ajax',['models'=>$models,'page' => $page,]);
		}else{
		return $this->render('index', [
		    'keyword'=>$keyword,
			'models'=>$models,
			'sort_data' => $sort_data, 'sort_order' => $sort_order, 'sort_selected' => $sort_selected,
			'filter_attr' => $filter_attr,
			'view' => $view,
			'page' => $page,
			'page_total'=>$page_total,
		]);
		}
	}
	public function actionGetSuggester(){
        $query = \Yii::$app->request->get("query");

        $access_key = \Yii::$app->params['open_search']['access_key'];
        $secret = \Yii::$app->params['open_search']['secret'];
        $host = "http://opensearch-cn-qingdao.aliyuncs.com";//根据自己的应用区域选择API
        $key_type = "aliyun";  //固定值，不必修改
        $opts = ['host' => $host];
        $client = new CloudsearchClient($access_key, $secret, $opts, $key_type);
        $suggest = new CloudsearchSuggest($client);

        $suggest->setIndexName("jiarun_item_db");
        $suggest->setSuggestName("search_notice");
        $suggest->setHits(10);
        $suggest->setQuery($query);

        $items = array();
        try {
            $result = json_decode($suggest->search(), true);
            if (!isset($result['errors'])) {
                if (isset($result['suggestions']) && !empty($result['suggestions'])) {
                    foreach ($result['suggestions'] as $item) {
                        $items[] = $item['suggestion'];
                    }
                } else {
                    $items = array();
                }
            } else {
                foreach ($result['errors'] as $error) {
                    throw new Exception($error['message'] . " request_id: " . $result['request_id'],$error['code']);
                }
            }
        } catch (Exception $e) {
            // Logging the error code and error message.
        }


         \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		return $items;
    }

}
